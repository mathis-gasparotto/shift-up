<?php

namespace App\Helper;

use App\Entity\User;
use DateTime;

/**
 *
 */
final class GlobalHelper
{
    /** @var string  */
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    /** @var string  */
    public const ROLE_USER = 'ROLE_USER';

    /** @var string  */
    public const PUBLIC_ACCESS = 'PUBLIC_ACCESS';

    /** @var string[]  */
    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_USER,
        self::PUBLIC_ACCESS,
    ];

    /**
     * @param string $email
     * @param string $username
     * @return string
     */
    public static function createToken(string $email, string $username): string
    {
        $token = base64_encode(
            hash_hmac('sha256', json_encode([$email, $username]), 'shift-up', true)
        );

        $arrayToken = str_split($token);
        $arrayNoAuthoriseChar = ['/','+','='];

        foreach ($arrayToken as $index => $character) {
            foreach ($arrayNoAuthoriseChar as $noAuthorizeChar) {
                if ($character === $noAuthorizeChar) {
                    unset($arrayToken[$index]);
                }
            }
        }

        $array = array_values($arrayToken);

        shuffle($array);

        return implode($array);
    }

    /**
     * @param User|null $user
     * @return bool
     */
    public static function isAdmin(?User $user): bool
    {
        return $user && in_array(GlobalHelper::ROLE_ADMIN, $user->getRoles());
    }
}
