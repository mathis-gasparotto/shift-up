<?php

namespace App\EventSubscribers;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Model\ManagerAwareInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 *
 */
class ManagerSubscriber implements EventSubscriberInterface
{
    /**
     * @param Security $security
     */
    public function __construct(private Security $security)
    {
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setManager', EventPriorities::PRE_VALIDATE]
        ];
    }

    /**
     * @param RequestEvent $event
     * @return void
     */
    public function setManager(RequestEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $user = $this->security->getUser();

        if (!$entity instanceof ManagerAwareInterface || $method !== Request::METHOD_POST) {
            return;
        }

        $entity->setManager($user);
        if (method_exists($entity, 'addUser')) {
            $entity->addUser($user);
        }
    }
}
