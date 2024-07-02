<?php

namespace App\DTO;

use App\Helper\ProjectHelper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
class ProjectGenerateDocumentsDto
{
    /**
     * @var array
     */
    #[
        Assert\NotBlank(),
        Assert\Choice(choices: ProjectHelper::PROJECT_DOCUMENTS, multiple: true)
    ]
    private array $documents;

    /**
     * @return array
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }

    /**
     * @param array $subscription
     * @return void
     */
    public function setDocuments(array $subscription): void
    {
        $this->documents = $subscription;
    }
}
