<?php

namespace App\StateProviders;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Helper\GlobalHelper;
use App\Helper\RepositoryHelper;
use App\Service\RepositoryService;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class ProjectMeCollectionDataProvider implements ProviderInterface
{
    /**
     * @param Security $security
     * @param RepositoryService $repositoryService
     */
    public function __construct(
        private Security $security,
        private RepositoryService $repositoryService
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

        $isAdmin = GlobalHelper::isAdmin($this->security->getUser());

        if ($isAdmin) {
            return $this->repositoryService->createQueryBuilder($operation->getClass(), RepositoryHelper::createParamDql('p'), $operation, $context);
        }

        $teams = $this->security->getUser()->getTeams();
        $projects = [];
        foreach ($teams as $team) {
            $projects = array_merge($projects, $team->getProjects()->toArray());
        }

        return $projects;
    }
}
