<?php

namespace App\Helper;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

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

    /**
     * @param User|UserInterface $user
     * @param Project $project
     * @return void
     */
    public static function checkIfUserIsInProjectTeam(User|UserInterface $user, Project $project): void
    {
        $isAdmin = GlobalHelper::isAdmin($user);

        if (!$isAdmin && $project->getTeam()->getManager() !== $user && !$user->isInTeam($project->getTeam())) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @param User|UserInterface $user
     * @param Project $project
     * @return void
     */
    public static function checkIfUserIsProjectTeamManager(User|UserInterface $user, Project $project): void
    {
        $isAdmin = GlobalHelper::isAdmin($user);

        if (!$isAdmin && $project->getTeam()->getManager() !== $user && !$user->isInTeam($project->getTeam())) {
            throw new AccessDeniedException();
        }
    }
}