<?php

namespace App\StateProviders;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Helper\GlobalHelper;
use App\Repository\ProjectRepository;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class LastProjectDocumentByProjectGetDataProvider implements ProviderInterface
{
    /**
     * @param Security $security
     * @param ProjectRepository $projectRepository
     */
    public function __construct(
        private Security $security,
        private ProjectRepository $projectRepository
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
        $project = $this->projectRepository->find($uriVariables['id']);
        if (!$project) {
            return [];
        }
        $team = $project->getTeam();

        $user = $this->security->getUser();

        if (!GlobalHelper::isAdmin($user) && !$user->isInTeam($team) && $team->getManager() !==  $user) {
            throw new AccessDeniedException();
        }

        $class = GlobalHelper::getClassShortName($context['operation']->getClass());
        $getMethod = 'getLast' . $class;

        return $project->$getMethod();
    }
}
