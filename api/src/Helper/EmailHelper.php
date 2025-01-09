<?php

namespace App\Helper;

use Symfony\Component\Mime\Email;

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

    /** @var string[]  */
    public const EMAIL_SUBJECTS = [
        self::EMAIL_TYPE_CONFIRM_EMAIL => 'Confirm your email',
        self::EMAIL_TYPE_RESET_PASSWORD => 'Reset your password',
    ];

    /**
     * @param string $type
     * @param array $data
     * @return string
     */
    public static function getEmailContent(string $type, array $data): string
    {
        return match ($type) {
            self::EMAIL_TYPE_CONFIRM_EMAIL => self::confirmEmailContent($data),
            self::EMAIL_TYPE_RESET_PASSWORD => self::resetPasswordContent($data),
            default => throw new \Exception('Invalid email type'),
        };
    }

    /**
     * @param array $data
     * @return string
     */
    public static function confirmEmailContent(array $data): string
    {
        return "
            <p>Bonjour $data[user_first_name],</p>
            <p>Vous vous êtes inscrit sur ShiftUp.</p>
            <p>Cliquez sur le bouton ci-dessous pour confirmer votre adresse e-mail :</p>
            <p><a href='$data[confirmation_link]'>Confirmer mon adresse e-mail</a></p>
            <p>Si le bouton ci dessus ne fonctionne pas correctement, vous pouvez copier-coller le lien suivant dans votre navigateur : <a href='$data[confirmation_link]'>$data[confirmation_link]</a></p>
            <p>L’équipe ShiftUp</p>
        ";
    }

    /**
     * @param array $data
     * @return string
     */
    public static function resetPasswordContent(array $data): string
    {
        return "
            <p>Bonjour $data[user_first_name],</p>
            <p>Vous avez demandé un changement de mot de passe.</p>
            <p>Cliquez sur le bouton ci-dessous pour réinitialiser votre mot de passe :</p>
            <p><a href='$data[reset_link]'>Réinitialiser mon mot de passe</a></p>
            <p>Ce lien est valable pendant 10 minutes. Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer ce message.</p>
            <p>Si le bouton ci dessus ne fonctionne pas correctement, vous pouvez copier-coller le lien suivant dans votre navigateur : <a href='$data[reset_link]'>$data[reset_link]</a></p>
            <p>L’équipe ShiftUp</p>
        ";
    }
}