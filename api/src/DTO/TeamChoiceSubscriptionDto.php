<?php

namespace App\DTO;

use App\Entity\SubscriptionPrice;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
class TeamChoiceSubscriptionDto
{
    /**
     * @var SubscriptionPrice
     */
    #[
        Assert\NotBlank()
    ]
    private SubscriptionPrice $subscriptionPrice;

    /**
     * @return SubscriptionPrice
     */
    public function getSubscriptionPrice(): SubscriptionPrice
    {
        return $this->subscriptionPrice;
    }

    /**
     * @param SubscriptionPrice $subscriptionPrice
     * @return void
     */
    public function setSubscriptionPrice(SubscriptionPrice $subscriptionPrice): void
    {
        $this->subscriptionPrice = $subscriptionPrice;
    }
}
