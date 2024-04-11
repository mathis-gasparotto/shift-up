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

    /**
     * @param string $label
     * @return string
     */
    public static function getStripeSubscriptionDescription(string $label): string
    {
        return 'Souscription à l\'abonnement "' . $label . '"';
    }

}