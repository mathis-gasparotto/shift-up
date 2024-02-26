<?php

declare(strict_types=1);

namespace App\EventSubscribers;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\MediaObject;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * Class OwnerSubscriber
 * @package App\EventSubscriber
 */
class MediaObjectSubscriber implements EventSubscriberInterface
{
    /** @var Security $security */
    private Security $security;

    private EntityManagerInterface $entityManager;

    /**
     * AdviceSubscriber constructor.
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     * @param StorageInterface $storage
     * @param $filterService
     */
    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        private StorageInterface $storage,
        private $filterService
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['setMediaCache', EventPriorities::POST_WRITE]
            ]
        ];
    }

    /**
     * @param ViewEvent $event
     * @return void
     */
    public function setMediaCache(ViewEvent $event): void
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return;
        }

        if (!$entity instanceof MediaObject || Request::METHOD_POST !== $method) {
            return;
        }

        $path = $this->storage->resolvePath($entity, 'file');
        $this->filterService->getUrlOfFilteredImage($path, 'picture_thumb', null, false);
        $this->filterService->getUrlOfFilteredImage($path, 'picture_small', null, false);
        $this->filterService->getUrlOfFilteredImage($path, 'picture_large', null, false);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
