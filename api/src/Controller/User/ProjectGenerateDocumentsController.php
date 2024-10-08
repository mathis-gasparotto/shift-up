<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\DTO\ProjectGenerateDocumentsDto;
use App\Entity\Project;
use App\Helper\ProjectHelper;
use App\Service\ProjectDocumentService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class ProjectGenerateDocumentsController
 * @package App\Controller
 */
#[AsController]
class ProjectGenerateDocumentsController extends AbstractController
{
    /**
     * @param Project $project
     * @param ProjectGenerateDocumentsDto $data
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     * @param ProjectDocumentService $projectDocumentService
     * @return JsonResponse
     * @throws ORMException
     */
    public function __invoke(Project $project, #[MapRequestPayload] ProjectGenerateDocumentsDto $data, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, ProjectDocumentService $projectDocumentService): JsonResponse
    {
        ProjectHelper::checkIfUserIsInProjectTeam($this->getUser(), $project);

        return $this->json($projectDocumentService->generateDocuments($project, $data->getDocuments()));
    }
}
