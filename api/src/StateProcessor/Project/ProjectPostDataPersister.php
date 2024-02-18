<?php

declare(strict_types=1);

namespace App\StateProcessor\Project;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Helper\GlobalHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class ProjectPostDataPersister implements ProcessorInterface
{
    /**
     * ProjectPostDataPersister constructor.
     *
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager
    )
    {}

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return mixed
     * @throws Exception
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $isAdmin = GlobalHelper::isAdmin($this->security->getUser());

        if (!$isAdmin && $data->getTeam()->getManager() !== $this->security->getUser()) {
            throw new AccessDeniedException();
        }

        $data->getTeam()->addProject($data);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
