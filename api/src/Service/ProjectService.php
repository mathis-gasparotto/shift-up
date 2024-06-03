<?php

namespace App\Service;

use App\Entity\MediaObject;
use App\Helper\MediaObjectHelper;
use App\Repository\MediaObjectRepository;
use Exception;

/**
 * class ProjectService
 * package App\Service
 */
class ProjectService
{
    /**
     * @param MediaObjectRepository $mediaObjectRepository
     */
    public function __construct(
        private readonly MediaObjectRepository $mediaObjectRepository
    ) {
    }

    /**
     * @return array
     */
    public function getProjectMediaObjects(): array
    {
        return $this->mediaObjectRepository->findBy(['category' => MediaObjectHelper::PROJECT_CATEGORY]);
    }

    /**
     * @return MediaObject
     * @throws Exception
     */
    public function getRandomProjectPicture(): MediaObject
    {
        $mediaObjects = $this->getProjectMediaObjects();

        if (empty($mediaObjects)) {
            throw new Exception('No project pictures found');
        }

        return $mediaObjects[array_rand($mediaObjects)];
    }
}
