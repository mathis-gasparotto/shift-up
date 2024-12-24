<?php

namespace App\Messenger;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;

class GenerateDocumentsMessage
{
    public function __construct(
        private Uuid $projectId,
        private array $documents,
        private Uuid $authorId
    ) {}

    public function getProjectId(): Uuid
    {
        return $this->projectId;
    }

    public function getDocuments(): array
    {
        return $this->documents;
    }

    public function getAuthorId(): Uuid
    {
        return $this->authorId;
    }
}
