<?php

namespace App\Service;

use App\Entity\Subscription;
use App\Entity\Team;
use App\Entity\User;
use App\Helper\SubscriptionHelper;
use App\Repository\SubscriptionRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Event;
use Stripe\Exception\ApiErrorException;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Subscription as StripeSubscription;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 */
class StripeService
{
    /** @var StripeClient */
    private StripeClient $stripeClient;

    /**
     * @param string $appFrontUrl
     * @param string $stripeSk
     * @param string $stripeWk
     * @param string $stripeApiVersion
     * @param EntityManagerInterface $entityManager
     * @param TeamRepository $teamRepository
     * @param SubscriptionRepository $subscriptionRepository
     */
    public function __construct(
        private readonly string                 $appFrontUrl,
        private readonly string                 $stripeSk,
        private readonly string                 $stripeWk,
        private readonly string                 $stripeApiVersion,
        private readonly EntityManagerInterface $entityManager,
        private readonly TeamRepository         $teamRepository,
        private readonly UserRepository         $userRepository,
        private readonly SubscriptionRepository $subscriptionRepository
    )
    {
        Stripe::setApiKey($this->stripeSk);
        Stripe::setApiVersion($this->stripeApiVersion);
        $this->stripeClient = new StripeClient($this->stripeSk);
    }

    /**
     * @return SubscriptionService
     */
    private function setSubscriptionService(): SubscriptionService
    {
        return new SubscriptionService($this->entityManager, $this);
    }


    /**
     * @param User|UserInterface $user
     * @param Team $team
     * @param Subscription $subscription
     * @return Session
     * @throws ApiErrorException
     */
    public function startSession(User|UserInterface $user, Team $team, Subscription $subscription): Session
    {
        $customer = $this->getOrCreateStripeCustomer($team);

        return Session::create([
            'customer' => $customer->id,
            'line_items' => [
                [
                    'quantity' => 1,
                    'price' => $subscription->getStripePriceId()
                ]
            ],
            'mode' => 'subscription',
            'success_url' => $this->appFrontUrl . '/teams/' . $team->getId() . '/?subscribeSuccess=true',
            'cancel_url' => $this->appFrontUrl . '/teams/' . $team->getId() . '/',
            'billing_address_collection' => 'required',
            'metadata' => [
                'user_id' => $user->getId(), // for save on subscription success the user who choose the subscription
                'team_id' => $team->getId(),
                'subscription_id' => $subscription->getId(),
            ]
        ]);
    }

    /**
     * @param Team $team
     * @return Customer
     * @throws ApiErrorException
     */
    private function getOrCreateStripeCustomer(Team $team): Customer
    {
        $customer = null;

        $teamCustomerId = $team->getStripeCustomerId();
        if ($teamCustomerId) {
            $customer = $this->stripeClient->customers->retrieve($teamCustomerId);
        }

        if (!$customer) {
            $customers = $this->stripeClient->customers->search([
                'query' => 'email:"' . $team->getBillingEmail() . '"'
            ]);
            if (count($customers->data) < 1) {
                $customer = $this->stripeClient->customers->create([
                    'email' => $team->getBillingEmail(),
                    'name' => $team->getName()
                ]);
            } else {
                $customer = $customers->data[0];
            }

            $team->setStripeCustomerId($customer->id);
            $this->entityManager->persist($team);
            $this->entityManager->flush();
        } elseif (
            $customer->email !== $team->getBillingEmail() ||
            $customer->name !== $team->getName()
        ) {
            $this->stripeClient->customers->update($teamCustomerId, [
                'email' => $team->getBillingEmail(),
                'name' => $team->getName()
            ]);
        }

        return $customer;
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function confirmationPayment(): Response
    {
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = $this->stripeWk;

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return new Response($e, 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return new Response($e, 400);
        }

        $now = new \DateTime();
        // If first try of event created less or equal to 5min ago -> error
        if ($now->getTimestamp() - $event->created >= 300) {
            return new Response('[Checkout] Checkout timeout');
        }

        return match ($event->type) {
            'checkout.session.completed' => $this->confirmationPaymentCheckoutCompleted($event),
            'customer.subscription.updated' => $this->confirmationPaymentSubscriptionRenewal($event),
            default => new Response('Received unknown event type ' . $event->type),
        };
    }


    /**
     * @param Event $event
     * @return Response
     * @throws ApiErrorException
     * @throws \Exception
     */
    private function confirmationPaymentCheckoutCompleted(Event $event): Response
    {
        $currentUser = $this->userRepository->find($event->data->object->metadata->user_id);
        if (!$currentUser) {
            throw new NotFoundHttpException('User not found');
        }

        $team = $this->teamRepository->find($event->data->object->metadata->team_id);
        if (!$team) {
            throw new NotFoundHttpException('Team not found');
        }

        $description = $this->confirmPaymentCheckoutCompletedDataForSubscription($event, $currentUser, $team);

        if ($event->data->object->payment_intent) {
            $this->stripeClient->paymentIntents->update(
                $event->data->object->payment_intent,
                ['description' => $description]
            );
        }

        $team->setStripeCustomerId($event->data->customer);

        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return new Response('The checkout has been completed');
    }

    /**
     * @param Event $event
     * @param User $user
     * @param Team $team
     * @return string
     * @throws \DateMalformedStringException
     */
    private function confirmPaymentCheckoutCompletedDataForSubscription(Event $event, User $user, Team $team): string
    {
        $subscription = $this->subscriptionRepository->find($event->data->object->metadata->subscription_id);

        if (!$subscription) {
            throw new BadRequestException('Subscription not found');
        }

        $subscriptionService = $this->setSubscriptionService();
        $subscriptionService->confirmSubscription($subscription, $user, $team, $event->data->object->subscription);

        return "Souscription à l'abonement " . $subscription->getLabel();
    }

    /**
     * @param Event $event
     * @return Response
     * @throws \Exception
     */
    private function confirmationPaymentSubscriptionRenewal(Event $event): Response
    {
        [$team, $subscription] = $this->checkSubscriptionForRenewal($event);
        return new Response(json_encode(["team" => $team, "sub" =>$subscription]));



        $subscriptionService = $this->setSubscriptionService();
        $subscriptionService->subscriptionRenewal($subscription, $team);

        return new Response('The renewal has been completed');
    }

    /**
     * @param Event $event
     * @return array
     */
    private function checkSubscriptionForRenewal(Event $event): array
    {
        $team = $this->teamRepository->findOneBy(['stripeSubscriptionId' => $event->data->object->id]);
        if (!$team) {
            throw new NotFoundHttpException('Team not found');
        }

        $subscription = $this->subscriptionRepository->findOneBy(['stripePriceId' => $event->data->object->plan->id]);
        if (!$subscription) {
            throw new NotFoundHttpException('Subscription not found');
        }

        return [$team, $subscription];
    }

    /**
     * @param Subscription $subscription
     * @return array
     * @throws ApiErrorException
     */
    public function createStripeSubscription(Subscription $subscription): array
    {
        $stripeProduct = $this->createStripeProduct($subscription->getLabel());
        $stripePrice = $this->createStripePrice($stripeProduct->id, $subscription);

        return [
            $stripeProduct,
            $stripePrice
        ];
    }

    /**
     * @param string $label
     * @return Product
     * @throws ApiErrorException
     */
    public function createStripeProduct(string $label): Product
    {
        return $this->stripeClient->products->create([
            'name' => $label,
            'description' => SubscriptionHelper::getStripeSubscriptionDescription($label)
        ]);
    }

    /**
     * @param string $productId
     * @param Subscription $subscription
     * @return Price
     * @throws ApiErrorException
     */
    public function createStripePrice(string $productId, Subscription $subscription): Price
    {
        $price = $subscription->getPrice();
        $recurring = $subscription->getRecurrence();

        return $this->stripeClient->prices->create([
            'unit_amount' => ($price * 100),
            'currency' => SubscriptionHelper::CURRENCY_EUR,
            'recurring' => ['interval' => $recurring],
            'product' => $productId
        ]);
    }

    /**
     * @param Subscription $subscription
     * @return Product
     * @throws ApiErrorException
     */
    public function updateStripeProductTitleFromSubscription(Subscription $subscription): Product
    {
        return $this->updateStripeProduct(
            $subscription->getStripeProductId(),
            ['name' => $subscription->getLabel()]
        );
    }

    /**
     * @param string $productId
     * @param array $newData
     * @return Product
     * @throws ApiErrorException
     */
    public function updateStripeProduct(string $productId, array $newData): Product
    {
        return $this->stripeClient->products->update(
            $productId,
            $newData
        );
    }

    /**
     * @param string $priceId
     * @param array $newData
     * @return Product
     * @throws ApiErrorException
     */
    public function updateStripePrice(string $priceId, array $newData): Product
    {
        return $this->stripeClient->products->update(
            $priceId,
            $newData
        );
    }

    /**
     * @param string $productId
     * @return void
     * @throws ApiErrorException
     */
    public function deactivateStripeProduct(string $productId): void
    {
        $this->updateStripeProduct($productId, ['active' => false]);
    }

    /**
     * @param string $subscriptionId
     * @return StripeSubscription
     * @throws ApiErrorException
     */
    public function cancelStripeSubscription(string $subscriptionId): StripeSubscription
    {
        return $this->stripeClient->subscriptions->cancel($subscriptionId);
    }

}
