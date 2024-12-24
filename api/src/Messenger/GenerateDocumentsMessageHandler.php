<?php

namespace App\Messenger;

use App\Entity\Project;
use App\Entity\User;
use App\Service\ProjectDocumentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
class GenerateDocumentsMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProjectDocumentService $projectDocumentService
    ) {}

    public function __invoke(GenerateDocumentsMessage $message)
    {
        $project = $this->entityManager->getRepository(Project::class)->find($message->getProjectId());
        $author = $this->entityManager->getRepository(User::class)->find($message->getAuthorId());

        $this->projectDocumentService->generateDocuments($project, $message->getDocuments(), $author);
    }
}
