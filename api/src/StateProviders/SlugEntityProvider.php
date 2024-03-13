<?php

namespace App\StateProviders;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * class SlugEntityProvider
 * package App\StateProviders
 */
class SlugEntityProvider implements ProviderInterface
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
    ) {
    }

    /**
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return object|array|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $isUuid = preg_match('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $uriVariables['slug']);
        if ($isUuid) {
            return $this->managerRegistry->getRepository($context["resource_class"])->findOneBy(['id' => $uriVariables['slug']]);
        } else {
            return $this->managerRegistry->getRepository($context["resource_class"])->findOneBy(['slug' => $uriVariables['slug']]);
        }
    }
}
