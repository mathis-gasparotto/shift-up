<?php

namespace App\Service;

use App\Entity\Subscription;
use App\Entity\Team;
use App\Entity\User;
use App\Helper\SubscriptionHelper;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Exception\ApiErrorException;

/**
 * class SubscriptionService
 * package App\Service
 */
class SubscriptionService
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param StripeService $stripeService
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StripeService $stripeService
    ) {
    }

    /**
     * @param Subscription $subscription
     * @param User $user
     * @param Team $team
     * @param string|null $subscriptionId
     * @return void
     * @throws \DateMalformedStringException
     */
    public function confirmSubscription(Subscription $subscription, User $user, Team $team, string $subscriptionId = null): void
    {
        $this->setSubscriptionToTeam($subscription, $team, $subscriptionId, $user);
        $this->sendConfirmSubscriptionEmail($team, $subscription);
    }

    /**
     * @param Subscription $subscription
     * @param Team $team
     * @param string|null $subscriptionId
     * @return void
     * @throws \DateMalformedStringException
     */
    public function subscriptionRenewal(Subscription $subscription, Team $team, string $subscriptionId = null): void
    {
        $this->setSubscriptionToTeam($subscription, $team, $subscriptionId, null);
    }


    /**
     * @param Team $team
     * @return void
     * @throws ApiErrorException
     */
    public function cancelSubscription(Team $team): void
    {
        $this->stripeService->cancelStripeSubscription($team->getStripeSubscriptionId());
        $this->persistTeamSubscriptionCancellation($team);
        $this->sendCancelSubscriptionEmail($team);
    }

    /**
     * @param Team $team
     * @return void
     */
    private function persistTeamSubscriptionCancellation(Team $team): void
    {
        $team->setStripeSubscriptionId(null);

        $this->entityManager->persist($team);
        $this->entityManager->flush();
    }

    /**
     * @param Team $team
     * @return void
     */
    private function sendCancelSubscriptionEmail(Team $team): void
    {
        $email = $team->getManager()->getEmail();
        $teamName = $team->getName();
        // TODO: send email
    }

    /**
     * @param Subscription $subscription
     * @param Team $team
     * @param string|null $subscriptionId
     * @param User|null $user
     * @return void
     * @throws \DateMalformedStringException
     */
    private function setSubscriptionToTeam(Subscription $subscription, Team $team, string $subscriptionId = null, User $user = null): void
    {
        $endDate = $this->getSubscriptionEndAtDate($subscription);
        $team->setSubscriptionEndAt($endDate);
        $team->setSubscription($subscription);
        if ($user) {
            $team->setSubscriptionChooser($user);
        }
        if ($subscriptionId) {
            $team->setStripeSubscriptionId($subscriptionId);
        }

        $this->entityManager->persist($team);
        $this->entityManager->flush();
    }

    /**
     * @param Subscription $subscription
     * @return DateTime
     * @throws \DateMalformedStringException
     */
    private function getSubscriptionEndAtDate(Subscription $subscription): DateTime
    {
        $date = new DateTime();
        switch ($subscription->getRecurrence()) {
            case SubscriptionHelper::SUBSCRIPTION_RECURRENCE_MONTH:
                $date->modify('+1 month');
                break;
            case SubscriptionHelper::SUBSCRIPTION_RECURRENCE_YEAR:
                $date->modify('+1 year');
                break;
        }
        return $date->setTime(0, 0);
    }

    /**
     * @param Team $team
     * @param Subscription $subscription
     * @return void
     */
    private function sendConfirmSubscriptionEmail(Team $team, Subscription $subscription): void
    {
        $email = $team->getManager()->getEmail();
        $teamName = $team->getName();
        // TODO: send email
    }
}
