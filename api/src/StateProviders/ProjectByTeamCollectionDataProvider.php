<?php

namespace App\StateProviders;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Helper\GlobalHelper;
use App\Repository\TeamRepository;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class ProjectByTeamCollectionDataProvider implements ProviderInterface
{
    /**
     * @param Security $security
     * @param TeamRepository $teamRepository
     */
    public function __construct(
        private Security $security,
        private TeamRepository $teamRepository
    ) {
    }

    /**
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return array|object|null
     * @throws \Exception
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|null|object
    {
        $team = $this->teamRepository->find($uriVariables['id']);
        if (!$team) {
            return [];
        }

        if (!GlobalHelper::isAdmin($this->security->getUser()) && !$this->security->getUser()->isInTeam($team)) {
            return [];
        }

        return $team->getProjects()->toArray();
    }
}
