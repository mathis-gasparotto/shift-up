<?php

namespace App\Service;

use App\Entity\Subscription;
use App\Entity\SubscriptionPrice;
use App\Entity\Team;
use App\Entity\User;
use App\Helper\SubscriptionHelper;
use App\Repository\SubscriptionPriceRepository;
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
use Stripe\SubscriptionSchedule;
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
     * @param UserRepository $userRepository
     * @param SubscriptionPriceRepository $subscriptionPriceRepository
     */
    public function __construct(
        private readonly string                 $appFrontUrl,
        private readonly string                 $stripeSk,
        private readonly string                 $stripeWk,
        private readonly string                 $stripeApiVersion,
        private readonly EntityManagerInterface $entityManager,
        private readonly TeamRepository         $teamRepository,
        private readonly UserRepository         $userRepository,
        private readonly SubscriptionPriceRepository $subscriptionPriceRepository
    ) {
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
     * @param SubscriptionPrice $subscriptionPrice
     * @return Session
     * @throws ApiErrorException
     */
    public function startSession(User|UserInterface $user, Team $team, SubscriptionPrice $subscriptionPrice): Session
    {
        $customer = $this->getOrCreateStripeCustomer($team);

        return Session::create([
            'customer' => $customer->id,
            'line_items' => [
                [
                    'quantity' => 1,
                    'price' => $subscriptionPrice->getStripePriceId()
                ]
            ],
            'mode' => 'subscription',
            'success_url' => $this->appFrontUrl . '/teams/' . $team->getId() . '/?subscribeSuccess=true',
            'cancel_url' => $this->appFrontUrl . '/teams/' . $team->getId() . '/',
            'billing_address_collection' => 'required',
            'subscription_data' => [
                'metadata' => [
                    'team_id' => $team->getId(),
                    'user_id' => $user->getId(), // for save on subscription success the user who choose the subscription
                ]
            ],
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
            'customer.subscription.updated' => $this->confirmationPaymentSubscriptionUpdate($event),
            'customer.subscription.deleted' => $this->confirmationSubscriptionCanceled($event),
            'subscription_schedule.released' => $this->confirmationSubscriptionScheduleReleased($event),
            'customer.subscription.created' => $this->confirmationSubscriptionCreated($event),
            'subscription_schedule.created' => $this->confirmationSubscriptionScheduleCreated($event),
            'subscription_schedule.canceled' => $this->confirmationSubscriptionScheduleCanceled($event),
            default => new Response('Received unknown event type ' . $event->type),
        };
    }

    /**
     * @param Event $event
     * @return Response
     * @throws \Exception
     */
    private function confirmationSubscriptionCanceled(Event $event): Response
    {
        $team = $this->checkSubscriptionForCancellation($event);

        $subscriptionService = $this->setSubscriptionService();
        $subscriptionService->persistTeamSubscriptionCancellation($team);

        $team->setStripeCustomerId($event->data->object->customer);
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return new Response('The cancellation has been completed');
    }

    /**
     * @param Event $event
     * @return Team
     */
    private function checkSubscriptionForCancellation(Event $event): Team
    {
        $team = $this->teamRepository->findOneBy(['stripeSubscriptionId' => $event->data->object->id]);
        if (!$team) {
            throw new NotFoundHttpException('Team not found');
        }

        return $team;
    }

    /**
     * @param Event $event
     * @return Response
     * @throws \Exception
     */
    private function confirmationSubscriptionCreated(Event $event): Response
    {
        [$user, $team, $subscriptionPrice] = $this->checkSubscriptionForSubscriptionCreated($event);

        $endDate = $this->getSubscriptionEndAtDate($event);

        $subscriptionService = $this->setSubscriptionService();

        $subscriptionService->confirmSubscription($subscriptionPrice, $user, $team, $endDate, $event->data->object->id);

        $team->setStripeCustomerId($event->data->object->customer);
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return new Response('The subscription has been created');
    }

    /**
     * @param Event $event
     * @return array
     */
    private function checkSubscriptionForSubscriptionCreated(Event $event): array
    {
        $user = $this->userRepository->find($event->data->object->metadata->user_id);
        if (!$user) {
            throw new NotFoundHttpException(message: 'User not found');
        }

        $team = $this->teamRepository->find($event->data->object->metadata->team_id);
        if (!$team) {
            throw new NotFoundHttpException(message: 'Team not found');
        }

        $subscriptionPrice = $this->subscriptionPriceRepository->findOneBy(['stripePriceId' => $event->data->object->plan->id]);
        if (!$subscriptionPrice) {
            throw new NotFoundHttpException('Subscription price not found');
        }

        return [$user, $team, $subscriptionPrice];
    }

    /**
     * @param Event $event
     * @return Response
     * @throws \Exception
     */
    private function confirmationSubscriptionScheduleCreated(Event $event): Response
    {
        $team = $this->checkSubscriptionForSubscriptionScheduleCreated($event);

        $team->setStripeSubscriptionScheduleId($event->data->object->id);
        $team->setStripeCustomerId($event->data->object->customer);
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return new Response('The subscription schedule has been created');
    }

    /**
     * @param Event $event
     * @return Team
     */
    private function checkSubscriptionForSubscriptionScheduleCreated(Event $event): Team
    {
        $team = $this->teamRepository->find($event->data->object->metadata->team_id);
        if (!$team) {
            throw new NotFoundHttpException(message: 'Team not found');
        }

        return $team;
    }

    /**
     * @param Event $event
     * @return Response
     * @throws \Exception
     */
    private function confirmationSubscriptionScheduleReleased(Event $event): Response
    {
        [$team, $subscriptionPrice] = $this->checkSubscriptionForScheduleRelease($event);

        $endDate = $this->getSubscriptionScheduleEndAtDate($event);

        $subscriptionService = $this->setSubscriptionService();
        $subscriptionService->subscriptionUpdate($subscriptionPrice, $team, $endDate);

        $team->setStripeSubscriptionScheduleId(null);
        $team->setStripeCustomerId($event->data->object->customer);
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return new Response('The subscription schedule has been released');
    }

    /**
     * @param Event $event
     * @return array
     */
    private function checkSubscriptionForScheduleRelease(Event $event): array
    {
        $team = $this->teamRepository->findOneBy(['stripeSubscriptionScheduleId' => $event->data->object->id]);
        if (!$team) {
            throw new NotFoundHttpException('Team not found');
        }

        $subscriptionPrice = $this->subscriptionPriceRepository->findOneBy(['stripePriceId' => $event->data->object->phases[0]->items[0]->price]);
        if (!$subscriptionPrice) {
            throw new NotFoundHttpException('Subscription price not found');
        }

        return [$team, $subscriptionPrice];
    }

    /**
     * @param Event $event
     * @return Response
     * @throws \Exception
     */
    private function confirmationSubscriptionScheduleCanceled(Event $event): Response
    {
        $team = $this->checkSubscriptionForScheduleCanceled($event);

        $team->setStripeSubscriptionScheduleId(null);
        $team->setStripeCustomerId($event->data->object->customer);
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return new Response('The subscription schedule has been deleted');
    }

    /**
     * @param Event $event
     * @return Team
     */
    private function checkSubscriptionForScheduleCanceled(Event $event): Team
    {
        $team = $this->teamRepository->findOneBy(['stripeSubscriptionScheduleId' => $event->data->object->id]);
        if (!$team) {
            throw new NotFoundHttpException('Team not found');
        }

        return $team;
    }

    /**
     * @param Event $event
     * @return Response
     * @throws \Exception
     */
    private function confirmationPaymentSubscriptionUpdate(Event $event): Response
    {
        [$team, $subscriptionPrice] = $this->checkSubscriptionForUpdate($event);

        $endDate = $this->getSubscriptionEndAtDate($event);

        $subscriptionService = $this->setSubscriptionService();
        $subscriptionService->subscriptionUpdate($subscriptionPrice, $team, $endDate, $event->data->object->id);

        $team->setStripeCustomerId($event->data->object->customer);
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return new Response('The renewal has been completed');
    }

    /**
     * @param Event $event
     * @return array
     */
    private function checkSubscriptionForUpdate(Event $event): array
    {
        $team = $this->teamRepository->findOneBy(['stripeSubscriptionId' => $event->data->object->id]);
        if (!$team) {
            throw new NotFoundHttpException('Team not found');
        }

        $subscriptionPrice = $this->subscriptionPriceRepository->findOneBy(['stripePriceId' => $event->data->object->plan->id]);
        if (!$subscriptionPrice) {
            throw new NotFoundHttpException('Subscription price not found');
        }

        return [$team, $subscriptionPrice];
    }

    /**
     * @param Subscription $subscription
     * @return array
     * @throws ApiErrorException
     */
    public function createStripeSubscription(Subscription $subscription): array
    {
        $stripeProduct = $this->createStripeProduct($subscription->getLabel());
        $stripePrice = $this->createStripePrices($stripeProduct->id, $subscription);

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
     * @return array
     * @throws ApiErrorException
     */
    public function createStripePrices(string $productId, Subscription $subscription): array
    {
        $prices = $subscription->getPrices()->toArray();

        $toReturn = [];
        foreach ($prices as $price) {
            $toReturn[$price->getRecurrence()] = $this->stripeClient->prices->create([
                'unit_amount' => ($price->getPrice()),
                'currency' => SubscriptionHelper::CURRENCY_EUR,
                'recurring' => ['interval' => $price->getRecurrence()],
                'product' => $productId
            ]);
        }

        return $toReturn;
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
     * @throws NotFoundHttpException
     */
    public function cancelStripeSubscription(string $subscriptionId): StripeSubscription
    {
        $subscription = $this->stripeClient->subscriptions->retrieve($subscriptionId);

        if (!$subscription) {
            throw new NotFoundHttpException('Subscription not found');
        }

        if ($subscription->status === 'active') {
            return $this->stripeClient->subscriptions->update($subscriptionId, [
                'cancel_at_period_end' => true
            ]);
        }

        return $this->stripeClient->subscriptions->cancel($subscriptionId);
    }

    /**
     * @param Team $team
     * @param SubscriptionPrice $newSubscription
     * @return StripeSubscription
     * @throws ApiErrorException
     */
    public function changeSubscription(User|UserInterface $user, Team $team, SubscriptionPrice $newSubscription): SubscriptionSchedule
    {
        $subscription = $this->cancelStripeSubscription($team->getStripeSubscriptionId());
        return $this->stripeClient->subscriptionSchedules->create([
            'customer' => $team->getStripeCustomerId(),
            'start_date' => $subscription->current_period_end,
            'end_behavior' => 'release',
            'metadata' => [
                'team_id' => $team->getId()
            ],
            'phases' => [
                [
                    'items' => [
                        [
                            'price' => $newSubscription->getStripePriceId(),
                            'quantity' => 1,
                        ],
                    ],
                    'metadata' => [
                        'user_id' => $user->getId(),
                        'team_id' => $team->getId()
                    ],
                    'proration_behavior' => 'none'
                ]
            ]
        ]);
    }

    /**
     * @param Event $event
     * @return \DateTime
     */
    private function getSubscriptionEndAtDate(Event $event): \DateTime
    {
        $date = (new \DateTime())->setTimestamp($event->data->object->current_period_end);
        return $date->setTime(0, 0);
    }

    /**
     * @param Event $event
     * @return \DateTime
     */
    private function getSubscriptionScheduleEndAtDate(Event $event): \DateTime
    {
        $date = (new \DateTime())->setTimestamp($event->data->object->phases[0]->end_date);
        return $date->setTime(0, 0);
    }
}