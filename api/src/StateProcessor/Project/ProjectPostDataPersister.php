<?php

declare(strict_types=1);

namespace App\StateProcessor\Project;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Helper\ProjectHelper;
use App\Service\ProjectService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;

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
     * @param ProjectService $projectService
     */
    public function __construct(
        private readonly Security               $security,
        private readonly EntityManagerInterface $entityManager,
        private readonly ProjectService         $projectService
    ) {}

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return mixed
     * @throws Exception
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        ProjectHelper::checkIfUserIsProjectTeamManager($this->security->getUser(), $data);
        $data->setPicture($this->projectService->getRandomProjectPicture());

        $data->getTeam()->addProject($data);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
