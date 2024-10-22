<?php

namespace App\StateProcessor\Team;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * class TeamDeleteDataPersister
 * package App\StateProcessor\User
 */
class TeamDeleteDataPersister implements ProcessorInterface
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
        if (!$data->isDeletable()) {
            throw new UnprocessableEntityHttpException('This team cannot be deleted');
        }

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
