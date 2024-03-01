<?php

namespace App\StateProviders;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Helper\TeamHelper;
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

        TeamHelper::checkIfUserIsInTeam($this->security->getUser(), $team);

        return $team->getProjects()->toArray();
    }
}
