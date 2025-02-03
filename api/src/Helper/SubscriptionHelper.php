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
        'prices' => [
            [
                'price' => 0,
                'recurrence' => self::SUBSCRIPTION_RECURRENCE_MONTH,
            ],
        ],
        'description' => 'Abonnement gratuit',
    ];

    /** @var array  */
    public const SUBSCRIPTION_OBJECT_PREMIUM = [
        'label' => 'Premium',
        'prices' => [
            [
                'price' => 999,
                'recurrence' => self::SUBSCRIPTION_RECURRENCE_MONTH,
                'stripePriceId' => 'price_1QITrJRqJSF5LE3iBAY99q4W',
            ],
            [
                'price' => 9999,
                'recurrence' => self::SUBSCRIPTION_RECURRENCE_YEAR,
                'stripePriceId' => 'price_1QRzuTRqJSF5LE3ijfJlqXbJ',
            ],
        ],
        'description' => 'Abonnement premium pour indépendants',
        'stripeProductId' => 'prod_RApWpatdruBwcS',
    ];

    /** @var array  */
    public const SUBSCRIPTION_OBJECT_ENTERPRISE = [
        'label' => 'Enterprise',
        'prices' => [
            [
                'price' => 1999,
                'recurrence' => self::SUBSCRIPTION_RECURRENCE_MONTH,
                'stripePriceId' => 'price_1QITsvRqJSF5LE3inDgXRcGr',
            ],
            [
                'price' => 19999,
                'recurrence' => self::SUBSCRIPTION_RECURRENCE_YEAR,
                'stripePriceId' => 'price_1QRzx5RqJSF5LE3ifapvGJmz',
            ],
        ],
        'description' => 'Abonnement entreprise pour les entreprises',
        'stripeProductId' => 'prod_RApYvg0Vdst10c',
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
