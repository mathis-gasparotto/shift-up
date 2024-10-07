<?php

namespace App\Helper;

final class SubscriptionHelper
{
    /** @var string  */
    public const CURRENCY_EUR = 'eur';

    /** @var string  */
    public const SUBSCRIPTION_RECURRENCE_MONTH = 'MONTH';

    /** @var string  */
    public const SUBSCRIPTION_RECURRENCE_YEAR = 'YEAR';

    /** @var string[]  */
    public const SUBSCRIPTION_RECURRENCES = [
        self::SUBSCRIPTION_RECURRENCE_MONTH,
        self::SUBSCRIPTION_RECURRENCE_YEAR,
    ];

    /** @var array  */
    public const SUBSCRIPTION_OBJECT_FREE = [
        'label' => 'Free',
        'price' => 0,
        'recurrence' => self::SUBSCRIPTION_RECURRENCE_MONTH,
        'description' => 'Abonnement gratuit',
    ];

    /** @var array  */
    public const SUBSCRIPTION_OBJECT_PREMIUM = [
        'label' => 'Premium',
        'price' => 9.99,
        'recurrence' => self::SUBSCRIPTION_RECURRENCE_MONTH,
        'description' => 'Abonnement premium pour indépendants',
        'stripeProductId' => 'prod_1234567890',
        'stripePriceId' => 'price_1234567890',
    ];

    /** @var array  */
    public const SUBSCRIPTION_OBJECT_ENTERPRISE = [
        'label' => 'Enterprise',
        'price' => 19.99,
        'recurrence' => self::SUBSCRIPTION_RECURRENCE_MONTH,
        'description' => 'Abonnement entreprise pour équipes',
        'stripeProductId' => 'prod_1234567890',
        'stripePriceId' => 'price_1234567890',
    ];

    /** @var array[]  */
    public const SUBSCRIPTION_OBJECTS = [
        self::SUBSCRIPTION_OBJECT_FREE,
        self::SUBSCRIPTION_OBJECT_PREMIUM,
        self::SUBSCRIPTION_OBJECT_ENTERPRISE,
    ];

    /**
     * @param string $label
     * @return string
     */
    public static function getStripeSubscriptionDescription(string $label): string
    {
        return 'Souscription à l\'abonnement "' . $label . '"';
    }

}