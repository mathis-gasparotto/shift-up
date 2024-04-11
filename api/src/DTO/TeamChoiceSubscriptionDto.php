<?php

namespace App\DTO;

use App\Entity\Subscription;
use App\Entity\Team;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
class TeamChoiceSubscriptionDto
{
    /**
     * @var Subscription
     */
    #[
        Assert\NotBlank()
    ]
    private Subscription $subscription;

    /**
     * @return Subscription
     */
    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }

    /**
     * @param Subscription $subscription
     * @return void
     */
    public function setSubscription(Subscription $subscription): void
    {
        $this->subscription = $subscription;
    }
}
