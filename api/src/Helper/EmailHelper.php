<?php

namespace App\Helper;

/**
 *
 */
final class EmailHelper
{
    /** @var string  */
    public const DEFAULT_EMAIL_ADDRESS = 'no-reply@shift-up.ai';

    /** @var string  */
    public const DEFAULT_EMAIL_ADDRESS_NAME = 'ShiftUp';

    /** @var string  */
    public const EMAIL_TYPE_CONFIRM_EMAIL = 'confirm_email';

    /** @var string  */
    public const EMAIL_TYPE_RESET_PASSWORD = 'reset_password';

    /** @var int[]  */
    public const MAILJET_EMAILS = [
        self::EMAIL_TYPE_CONFIRM_EMAIL => 123456,
        self::EMAIL_TYPE_RESET_PASSWORD => 654321,
    ];

    /** @var string[]  */
    public const EMAIL_SUBJECTS = [
        self::EMAIL_TYPE_CONFIRM_EMAIL => 'Confirm your email',
        self::EMAIL_TYPE_RESET_PASSWORD => 'Reset your password',
    ];
}
