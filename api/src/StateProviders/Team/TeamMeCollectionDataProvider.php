<?php

namespace App\StateProviders\Team;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Helper\GlobalHelper;
use App\Helper\RepositoryHelper;
use App\Service\RepositoryService;
use Symfony\Bundle\SecurityBundle\Security;

/**
 *
 */
class TeamMeCollectionDataProvider implements ProviderInterface
{
    /**
     * @param Security $security
     * @param RepositoryService $repositoryService
     */
    public function __construct(
        private Security $security,
        private RepositoryService $repositoryService
    ) {}

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
            return $this->repositoryService->createQueryBuilder($operation->getClass(), RepositoryHelper::createParamDql('t'), $operation, $context);
        }

        return $this->security->getUser()->getTeams();
    }
}
