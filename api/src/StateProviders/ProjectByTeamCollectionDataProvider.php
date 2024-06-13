<?php

namespace App\StateProviders;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Project;
use App\Helper\TeamHelper;
use App\Repository\ProjectRepository;
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
     * @param ProjectRepository $projectRepository
     */
    public function __construct(
        private Security $security,
        private TeamRepository $teamRepository,
        private ProjectRepository $projectRepository
    ) {
    }

    /**
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return Project[]
     * @throws \Exception
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $team = $this->teamRepository->find($uriVariables['id']);
        if (!$team) {
            throw new \Exception('Team not found');
        }

        TeamHelper::checkIfUserIsInTeam($this->security->getUser(), $team);

        return $this->projectRepository->findBy(['team' => $team], ['updatedAt' => 'DESC']);
    }
}
