<?php

namespace App\StateProviders;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Helper\MediaObjectHelper;
use App\Repository\MediaObjectRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * class SlugEntityProvider
 * package App\StateProviders
 */
class ProjectMediaObjectProvider implements ProviderInterface
{
    /**
     * @param MediaObjectRepository $mediaObjectRepository
     */
    public function __construct(
        private readonly MediaObjectRepository $mediaObjectRepository,
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
        return $this->mediaObjectRepository->findBy(['category' => MediaObjectHelper::PROJECT_CATEGORY]);
    }
}
