<?php

namespace App\StateProviders;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Validator\ValidatorInterface;
use App\Helper\GlobalHelper;
use App\Helper\RepositoryHelper;
use App\Service\RepositoryService;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class TeamMeCollectionDataProvider implements ProviderInterface
{
    /**
     * @param Security $security
     * @param RepositoryService $repositoryService
     * @param ValidatorInterface $validator
     */
    public function __construct(
        private Security $security,
        private RepositoryService $repositoryService,
        private ValidatorInterface $validator
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

        $isAdmin = $this->security->getUser() && in_array(GlobalHelper::ROLE_ADMIN, $this->security->getUser()->getRoles());

        if ($isAdmin) {
            return $this->repositoryService->createQueryBuilder($operation->getClass(), RepositoryHelper::createParamDql('t'), $operation, $context);
        }

        return $this->security->getUser()->getTeams();
    }
}
