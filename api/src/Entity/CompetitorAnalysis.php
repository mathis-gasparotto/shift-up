<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Project\ProjectDownloadDocumentController;
use App\Controller\Project\ProjectDownloadSpecificDocumentController;
use App\Controller\Project\ProjectGenerateDocumentController;
use App\Helper\GlobalHelper;
use App\Model\OwnerAwareInterface;
use App\Model\TracingAwareInterface;
use App\Model\Traits\OwnerTrait;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\CompetitorAnalysisRepository;
use App\StateProcessor\Project\ProjectDocumentPostDataPersister;
use App\StateProviders\Project\LastProjectDocumentByProjectGetDataProvider;
use App\StateProviders\Project\ProjectDocumentByProjectCollectionDataProvider;
use Doctrine\DBAL\Types\Types;
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
                                        'example' => '/projects/{id}'
                                    ],
                                    'xAxisLabel' => [
                                        'type' => 'string',
                                    ],
                                    'yAxisLabel' => [
                                        'type' => 'string',
                                    ],
                                    'competitors' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'name' => [
                                                    'type' => 'string'
                                                ],
                                                'xPosition' => [
                                                    'type' => 'number',
                                                    'format' => 'float'
                                                ],
                                                'yPosition' => [
                                                    'type' => 'number',
                                                    'format' => 'float'
                                                ],
                                            ],
                                        ],
                                    ],
                                    'ourPosition' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'xPosition' => [
                                                'type' => 'number',
                                                'format' => 'float'
                                            ],
                                            'yPosition' => [
                                                'type' => 'number',
                                                'format' => 'float'
                                            ],
                                        ],
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            normalizationContext: [
                'openapi_definition_name' => 'PostCollection'
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_ADMIN . '")',
            processor: ProjectDocumentPostDataPersister::class
        ),
        new Post(
            uriTemplate: '/projects/{id}/competitor_analyses/generate',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            controller: ProjectGenerateDocumentController::class,
            normalizationContext: [
                'openapi_definition_name' => 'PostCollection'
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            write: false
        ),
        new Post(
            uriTemplate: '/projects/{id}/competitor_analyses/last/download',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            controller: ProjectDownloadDocumentController::class,
            normalizationContext: [
                'openapi_definition_name' => 'PostCollection'
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            write: false
        ),
        new Post(
            uriTemplate: '/competitor_analyses/{id}/download',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            controller: ProjectDownloadSpecificDocumentController::class,
            normalizationContext: [
                'openapi_definition_name' => 'PostCollection'
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            write: false
        ),
        new Get(
            uriTemplate: '/competitor_analyses/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'competitor_analysis:read',
                    'competitor_analysis:item:read'
                ]
            ],
            security: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object.getProject().getTeam())) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getProject().getTeam().getManager() === user)
            '
        ),
        new Put(
            uriTemplate: '/competitor_analyses/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'PutItem'
            ],
            securityPostDenormalize: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object.getProject().getTeam())) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getProject().getTeam().getManager() === user)
            '
        ),
        new Delete(
            normalizationContext: [
                'openapi_definition_name' => 'DeleteItem'
            ],
            security: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object.getProject().getTeam())) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getProject().getTeam().getManager() === user)
            '
        )
    ]
)]
#[ApiResource(
    uriTemplate: '/projects/{id}/competitor_analyses',
    operations: [
        new Get(
            uriTemplate: '/projects/{id}/competitor_analyses/last',
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'competitor_analysis:read',
                    'competitor_analysis:item:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            provider: LastProjectDocumentByProjectGetDataProvider::class
        ),
        new GetCollection(
            uriTemplate: '/projects/{id}/competitor_analyses',
            normalizationContext: [
                'openapi_definition_name' => 'GetCollection',
                'groups' => [
                    'competitor_analysis:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            provider: ProjectDocumentByProjectCollectionDataProvider::class
        ),
    ],
    uriVariables: [
        'id' => new Link(
            toProperty: 'project',
            fromClass: Project::class
        )
    ]
)]
class CompetitorAnalysis implements TracingAwareInterface, OwnerAwareInterface
{
    use TracingAwareTrait;
    use OwnerTrait;

    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['competitor_analysis:read', 'project:item:read'])]
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
     * @var MediaObject|null
     */
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Groups(['competitor_analysis:item:read'])]
    private ?MediaObject $file = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups(['competitor_analysis:item:read'])]
    private ?string $xAxisLabel = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups(['competitor_analysis:item:read'])]
    private ?string $yAxisLabel = null;

    /**
     * @var array
     */
    #[ORM\Column(type: Types::JSON)]
    #[
        Assert\Collection([
            'name' => new Assert\NotBlank(),
            'xPosition' => [
                new Assert\NotBlank(),
                new Assert\Type(type: 'float'),
                new Assert\Range(min: -10, max: 10)
            ],
            'yPosition' => [
                new Assert\NotBlank(),
                new Assert\Type(type: 'float'),
                new Assert\Range(min: -10, max: 10)
            ],
        ]),
        Groups(['competitor_analysis:item:read', 'competitor_analysis:write'])
    ]
    private array $competitors = [];

    /**
     * @var array
     */
    #[ORM\Column(type: Types::JSON)]
    #[
        Assert\Collection([
            'xPosition' => [
                new Assert\NotBlank(),
                new Assert\Type(type: 'float'),
                new Assert\Range(min: -10, max: 10)
            ],
            'yPosition' => [
                new Assert\NotBlank(),
                new Assert\Type(type: 'float'),
                new Assert\Range(min: -10, max: 10)
            ],
        ]),
        Groups(['competitor_analysis:item:read', 'competitor_analysis:write'])
    ]
    private array $ourPosition = [];

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

    /**
     * @return MediaObject|null
     */
    public function getFile(): ?MediaObject
    {
        return $this->file;
    }

    /**
     * @param MediaObject|null $file
     * @return $this
     */
    public function setFile(?MediaObject $file): static
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getXAxisLabel(): ?string
    {
        return $this->xAxisLabel;
    }

    /**
     * @param string $xAxisLabel
     * @return $this
     */
    public function setXAxisLabel(string $xAxisLabel): static
    {
        $this->xAxisLabel = $xAxisLabel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getYAxisLabel(): ?string
    {
        return $this->yAxisLabel;
    }

    /**
     * @param string $yAxisLabel
     * @return $this
     */
    public function setYAxisLabel(string $yAxisLabel): static
    {
        $this->yAxisLabel = $yAxisLabel;

        return $this;
    }

    /**
     * @return array
     */
    public function getCompetitors(): array
    {
        return $this->competitors;
    }

    /**
     * @param array $competitors
     * @return $this
     */
    public function setCompetitors(array $competitors): static
    {
        $this->competitors = $competitors;

        return $this;
    }

    /**
     * @return array
     */
    public function getOurPosition(): array
    {
        return $this->ourPosition;
    }

    /**
     * @param array $ourPosition
     * @return CompetitorAnalysis
     */
    public function setOurPosition(array $ourPosition): static
    {
        $this->ourPosition = $ourPosition;

        return $this;
    }
}
