<?php

namespace App\Helper;

final class ProjectHelper
{
    /** @var string  */
    public const STATUS_ACTIVE = 'STATUS_ACTIVE';

    /** @var string  */
    public const STATUS_DEACTIVATED = 'STATUS_DEACTIVATED';

    /** @var string  */
    public const STATUS_SUBSCRIPTION_PENDING = 'STATUS_SUBSCRIPTION_PENDING';

    /** @var string[]  */
    public const STATUS = [
        self::STATUS_ACTIVE,
        self::STATUS_DEACTIVATED,
        self::STATUS_SUBSCRIPTION_PENDING,
    ];
}