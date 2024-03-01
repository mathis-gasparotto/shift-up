<?php

declare(strict_types=1);

namespace App\StateProcessor\Project;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Helper\ProjectHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
        ProjectHelper::checkIfUserIsProjectTeamManager($this->security->getUser(), $data);

        $data->getTeam()->addProject($data);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
