<?php

namespace App\StateProcessor\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * class UserDeleteDataPersister
 * package App\StateProcessor\User
 */
class UserDeleteDataPersister implements ProcessorInterface
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $teamsManaged = $data->getTeamsManaged()->toArray();

        foreach ($teamsManaged as $team) {
            if ($team->getUsers()->count() === 0 || ($team->getUsers()->count() === 1 && $team->getUsers()->contains($data))) {
                $this->entityManager->remove($team);
            } else {
                $data->removeTeamsManaged($team);

                $this->entityManager->persist($team);
                $this->entityManager->persist($data);
                $this->entityManager->flush();
            }
        }

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}