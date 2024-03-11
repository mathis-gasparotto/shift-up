<?php

namespace App\Helper;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Entity\Team;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

final class SubscriptionHelper
{
    /** @var string  */
    public const SUBSCRIPTION_RECURRENCE_MONTH = 'MONTH';

    /** @var string  */
    public const SUBSCRIPTION_RECURRENCE_YEAR = 'YEAR';

    /** @var string[]  */
    public const SUBSCRIPTION_RECURRENCES = [
        self::SUBSCRIPTION_RECURRENCE_MONTH,
        self::SUBSCRIPTION_RECURRENCE_YEAR,
    ];
}