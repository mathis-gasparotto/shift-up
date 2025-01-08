<?php

namespace App\StateProcessor\Team;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Helper\GlobalHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * class TeamPostDataPersister
 * package App\StateProcessor\User
 */
class TeamPostDataPersister implements ProcessorInterface
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security               $security
    ) {}

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $isAdmin = GlobalHelper::isAdmin($this->security->getUser());

        if (!$data->getSubscription() && !$isAdmin) {
            throw new UnprocessableEntityHttpException('You have to choose a subscription to create a team');
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
}
