<?php

namespace App\Helper;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Entity\Team;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

final class TeamHelper
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

    /**
     * @param User|UserInterface $user
     * @param Team $team
     * @return void
     */
    public static function checkIfUserIsInTeam(User|UserInterface $user, Team $team): void
    {
        $isAdmin = GlobalHelper::isAdmin($user);

        if (!$isAdmin && $team->getManager() !== $user && !$user->isInTeam($team)) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @param User|UserInterface $user
     * @param Team $team
     * @return void
     */
    public static function checkIfUserIsTeamManager(User|UserInterface $user, Team $team): void
    {
        $isAdmin = GlobalHelper::isAdmin($user);

        if (!$isAdmin && $team->getManager() !== $user && !$user->isInTeam($team)) {
            throw new AccessDeniedException();
        }
    }
}