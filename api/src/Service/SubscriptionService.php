<?php

namespace App\Service;

use App\Entity\Subscription;
use App\Entity\SubscriptionPrice;
use App\Entity\Team;
use App\Entity\User;
use App\Helper\SubscriptionHelper;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Event;
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
     * @param SubscriptionPrice $subscriptionPrice
     * @param User $user
     * @param Team $team
     * @param DateTime $endDate
     * @param string|null $subscriptionId
     * @return void
     */
    public function confirmSubscription(SubscriptionPrice $subscriptionPrice, User $user, Team $team, DateTime $endDate, string $subscriptionId = null): void
    {
        $this->setSubscriptionToTeam($subscriptionPrice, $team, $endDate, $subscriptionId, $user);
        $this->sendConfirmSubscriptionEmail($team, $subscriptionPrice);
    }

    /**
     * @param SubscriptionPrice $subscriptionPrice
     * @param Team $team
     * @param DateTime $endDate
     * @param string|null $subscriptionId
     * @return void
     */
    public function subscriptionRenewal(SubscriptionPrice $subscriptionPrice, Team $team, DateTime $endDate, string $subscriptionId = null): void
    {
        $this->setSubscriptionToTeam($subscriptionPrice, $team, $endDate, $subscriptionId, null);
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
     * @param SubscriptionPrice $subscriptionPrice
     * @param Team $team
     * @param DateTime $endDate
     * @param string|null $subscriptionId
     * @param User|null $user
     * @return void
     */
    private function setSubscriptionToTeam(SubscriptionPrice $subscriptionPrice, Team $team, DateTime $endDate, string $subscriptionId = null, User $user = null): void
    {
        $team->setSubscriptionEndAt($endDate);
        $team->setSubscriptionPrice($subscriptionPrice);
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
     * @param Team $team
     * @param SubscriptionPrice $subscriptionPrice
     * @return void
     */
    private function sendConfirmSubscriptionEmail(Team $team, SubscriptionPrice $subscriptionPrice): void
    {
        $email = $team->getManager()->getEmail();
        $teamName = $team->getName();
        // TODO: send email
    }
}
