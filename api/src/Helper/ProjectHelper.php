<?php

namespace App\Helper;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Entity\Project;
use App\Entity\Team;
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

    /** @var string  */
    public const PROJECT_DOCUMENT_BUSINESS_MODEL_CANVAS = 'business_model_canvas';

    /** @var string  */
    public const PROJECT_DOCUMENT_BUYER_PLAN = 'buyer_persona';

    /** @var string  */
    public const PROJECT_DOCUMENT_COMPETITOR_ANALYSIS = 'competitor_analysis';

    /** @var string  */
    public const PROJECT_DOCUMENT_GOLDEN_TRIANGLE = 'golden_triangle';

    /** @var string  */
    public const PROJECT_DOCUMENT_MARKETING_MIX_4P = 'marketing_mix4';

    /** @var string  */
    public const PROJECT_DOCUMENT_MARKETING_MIX_5P = 'marketing_mix5';

    /** @var string  */
    public const PROJECT_DOCUMENT_PESTEL = 'pestel';

    /** @var string  */
    public const PROJECT_DOCUMENT_SMART = 'smart';

    /** @var string  */
    public const PROJECT_DOCUMENT_STP = 'stp';

    /** @var string  */
    public const PROJECT_DOCUMENT_SWOT = 'swot';

    /** @var string[]  */
    public const PROJECT_DOCUMENTS = [
        self::PROJECT_DOCUMENT_BUSINESS_MODEL_CANVAS,
        self::PROJECT_DOCUMENT_BUYER_PLAN,
        self::PROJECT_DOCUMENT_COMPETITOR_ANALYSIS,
        self::PROJECT_DOCUMENT_GOLDEN_TRIANGLE,
        self::PROJECT_DOCUMENT_MARKETING_MIX_4P,
        self::PROJECT_DOCUMENT_MARKETING_MIX_5P,
        self::PROJECT_DOCUMENT_PESTEL,
        self::PROJECT_DOCUMENT_SMART,
        self::PROJECT_DOCUMENT_STP,
        self::PROJECT_DOCUMENT_SWOT,
    ];

    /** @var string[]  */
    public const PROJECT_DOCUMENTS_PREMIUM = [
        // self::PROJECT_DOCUMENT_MARKETING_MIX_4P,
        // self::PROJECT_DOCUMENT_MARKETING_MIX_5P,
        // self::PROJECT_DOCUMENT_PESTEL,
        // self::PROJECT_DOCUMENT_STP,
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
     * @param Team $team
     * @return void
     */
    public static function checkIfTeamIsPremium(Team $team): void
    {
        if (!$team->isPremium()) {
            throw new AccessDeniedException('Your team avantages are not enough to do this action');
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

    /**
     * @param Team $team
     * @param string|string[] $document
     * @return void
     * @throws AccessDeniedException
     */
    public static function checkIfTeamIsAllowedToGenerateDocuments(Team $team, string|array $documentOrDocuments): void
    {
        $documents = is_array($documentOrDocuments) ? $documentOrDocuments : [$documentOrDocuments];

        if (!$team->isPremium() && array_intersect($documents, self::PROJECT_DOCUMENTS_PREMIUM)) {
            throw new AccessDeniedException('Your team avantages are not enough to do this action');
        }
    }
}