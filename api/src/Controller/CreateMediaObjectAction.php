<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MediaObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 *
 */
final class CreateMediaObjectAction
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return MediaObject
     */
    public function __invoke(Request $request, EntityManagerInterface $entityManager): MediaObject
    {
        /** @var File $uploadedFile */
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $mediaObject = new MediaObject();
        $mediaObject->file = $uploadedFile;

        return $mediaObject;
    }
}
