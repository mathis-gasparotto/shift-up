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

    /**
     * @param string|object $class
     * @return string
     * @throws \ReflectionException
     */
    public static function getClassShortName(string|object $class): string
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        return (new \ReflectionClass($class))->getShortName();
    }

    /**
     * @param string $str
     * @return array
     */
    public static function splitStringByMarkdownTitle1(string $str): array
    {
        $resultArray = preg_split('/^# (.*)/m', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $resultArray = array_map('trim', $resultArray);
        $resultArray = array_map(fn($item) => str_replace("\r\n", "\n", $item), $resultArray);
        $resultArray = array_map(fn($item) => str_replace("\n\n", "\n", $item), $resultArray);
        $resultArray = array_chunk($resultArray, 2);
        return array_combine(array_column($resultArray, 0), array_column($resultArray, 1));
    }
}
