<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\DTO\ProjectGenerateDocumentsDto;
use App\Entity\Project;
use App\Helper\ProjectHelper;
use App\Messenger\GenerateDocumentsMessage;
use App\Service\ProjectDocumentService;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;

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
     * @param ProjectDocumentService $projectDocumentService
     * @param MessageBusInterface $messageBus
     * @return JsonResponse
     * @throws ORMException
     */
    public function __invoke(Project $project, #[MapRequestPayload] ProjectGenerateDocumentsDto $data, ProjectDocumentService $projectDocumentService, MessageBusInterface $messageBus): JsonResponse
    {
        ProjectHelper::checkIfUserIsInProjectTeam($this->getUser(), $project);

        $documents = $projectDocumentService->generateDocuments($project, $data->getDocuments(), $this->getUser());

        return $this->json($documents);


        // Dispatch le message asynchrone
        // $messageBus->dispatch(new GenerateDocumentsMessage(
        //     $project->getId(),
        //     $data->getDocuments(),
        //     $this->getUser()->getId()
        // ));
        // return $this->json(['status' => 'Document generation started']);
    }
}
