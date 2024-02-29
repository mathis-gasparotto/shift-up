<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Helper\GlobalHelper;
use App\Model\TracingAwareInterface;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\CompetitorAnalysisRepository;
use App\StateProcessor\Project\ProjectDocumentPostDataPersister;
use App\StateProviders\ProjectDocumentByProjectCollectionDataProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
#[ORM\Entity(repositoryClass: CompetitorAnalysisRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/competitor_analyses',
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'project' => [
                                        'type' => 'string',
                                        'example' =>'/projects/{id}'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            normalizationContext: [
                'openapi_definition_name' => 'PostCollection'
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            processor: ProjectDocumentPostDataPersister::class
        ),
    ]
)]
#[ApiResource(
    uriTemplate: '/projects/{id}/competitor_analyses',
    operations: [new GetCollection()],
    uriVariables: [
        'id' => new Link(
            toProperty: 'project',
            fromClass: Project::class
        )
    ],
    normalizationContext: [
        'groups' => [
            'competitor_analysis:read'
        ]
    ],
    security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
    provider: ProjectDocumentByProjectCollectionDataProvider::class
)]
class CompetitorAnalysis implements TracingAwareInterface
{
    use TracingAwareTrait;

    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['competitor_analysis:read'])]
    private ?Uuid $id = null;

    /**
     * @var Project|null
     */
    #[ORM\ManyToOne(inversedBy: 'competitorAnalyses')]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Assert\NotBlank,
        Groups(['competitor_analysis:read', 'competitor_analysis:write'])
    ]
    private ?Project $project = null;

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return Project|null
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * @param Project|null $project
     * @return $this
     */
    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }
}
