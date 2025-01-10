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
use App\Repository\SWOTRepository;
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
#[ORM\Entity(repositoryClass: SWOTRepository::class)]
#[ApiResource(
    uriTemplate: '/swots',
    operations: [
        new Post(
            uriTemplate: '/swots',
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
                                    'strengths' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string'
                                        ]
                                    ],
                                    'weaknesses' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string'
                                        ]
                                    ],
                                    'opportunities' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string'
                                        ]
                                    ],
                                    'threats' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string'
                                        ]
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
            uriTemplate: '/projects/{id}/swots/generate',
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
            uriTemplate: '/projects/{id}/swots/last/download',
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
            uriTemplate: '/swots/{id}/download',
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
            uriTemplate: '/swots/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'swot:read',
                    'swot:item:read'
                ]
            ],
            security: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object.getProject().getTeam())) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getProject().getTeam().getManager() === user)
            '
        ),
        new Put(
            uriTemplate: '/swots/{id}',
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
    uriTemplate: '/projects/{id}/swots',
    operations: [
        new Get(
            uriTemplate: '/projects/{id}/swots/last',
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'swot:read',
                    'swot:item:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            provider: LastProjectDocumentByProjectGetDataProvider::class
        ),
        new GetCollection(
            uriTemplate: '/projects/{id}/swots',
            normalizationContext: [
                'openapi_definition_name' => 'GetCollection',
                'groups' => [
                    'swot:read'
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
class SWOT implements TracingAwareInterface, OwnerAwareInterface
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
    #[Groups(['swot:read', 'project:item:read'])]
    private ?Uuid $id = null;

    /**
     * @var array|null
     */
    #[ORM\Column(type: Types::JSON)]
    #[
        Assert\Type(type: 'array'),
        Assert\All(
            constraints: [
                new Assert\Type(type: 'string')
            ]
        ),
        Groups(['swot:read', 'swot:write'])
    ]
    private ?array $strengths = null;

    /**
     * @var array|null
     */
    #[ORM\Column(type: Types::JSON)]
    #[
        Assert\Type(type: 'array'),
        Assert\All(
            constraints: [
                new Assert\Type(type: 'string')
            ]
        ),
        Groups(['swot:read', 'swot:write'])
    ]
    private ?array $weaknesses = null;

    /**
     * @var array|null
     */
    #[ORM\Column(type: Types::JSON)]
    #[
        Assert\Type(type: 'array'),
        Assert\All(
            constraints: [
                new Assert\Type(type: 'string')
            ]
        ),
        Groups(['swot:read', 'swot:write'])
    ]
    private ?array $opportunities = null;

    /**
     * @var array|null
     */
    #[ORM\Column(type: Types::JSON)]
    #[
        Assert\Type(type: 'array'),
        Assert\All(
            constraints: [
                new Assert\Type(type: 'string')
            ]
        ),
        Groups(['swot:read', 'swot:write'])
    ]
    private ?array $threats = null;

    /**
     * @var Project|null
     */
    #[ORM\ManyToOne(inversedBy: 'SWOTs')]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Assert\All(
            constraints: [
                new Assert\Type(type: 'string')
            ]
        ),
        Groups(['swot:read', 'swot:write'])
    ]
    private ?Project $project = null;

    /**
     * @var MediaObject|null
     */
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Groups(['swot:item:read'])]
    private ?MediaObject $file = null;

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return array|null
     */
    public function getStrengths(): ?array
    {
        return $this->strengths;
    }

    /**
     * @param array $strengths
     * @return $this
     */
    public function setStrengths(array $strengths): static
    {
        $this->strengths = $strengths;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getWeaknesses(): ?array
    {
        return $this->weaknesses;
    }

    /**
     * @param array $weaknesses
     * @return $this
     */
    public function setWeaknesses(array $weaknesses): static
    {
        $this->weaknesses = $weaknesses;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getOpportunities(): ?array
    {
        return $this->opportunities;
    }

    /**
     * @param array $opportunities
     * @return $this
     */
    public function setOpportunities(array $opportunities): static
    {
        $this->opportunities = $opportunities;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getThreats(): ?array
    {
        return $this->threats;
    }

    /**
     * @param array $threats
     * @return $this
     */
    public function setThreats(array $threats): static
    {
        $this->threats = $threats;

        return $this;
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
}
