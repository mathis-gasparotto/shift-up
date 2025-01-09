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
use App\Repository\BusinessModelCanvasRepository;
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
#[ORM\Entity(repositoryClass: BusinessModelCanvasRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/business_model_canvas',
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
                                    'keyPartners' => [
                                        'type' => 'string'
                                    ],
                                    'keyActivities' => [
                                        'type' => 'string'
                                    ],
                                    'keyResources' => [
                                        'type' => 'string'
                                    ],
                                    'valuePropositions' => [
                                        'type' => 'string'
                                    ],
                                    'customerRelationships' => [
                                        'type' => 'string'
                                    ],
                                    'channels' => [
                                        'type' => 'string'
                                    ],
                                    'customerSegments' => [
                                        'type' => 'string'
                                    ],
                                    'costStructure' => [
                                        'type' => 'string'
                                    ],
                                    'revenueStreams' => [
                                        'type' => 'string'
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
        new Post(
            uriTemplate: '/projects/{id}/business_model_canvas/generate',
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
            uriTemplate: '/projects/{id}/business_model_canvas/last/download',
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
            uriTemplate: '/business_model_canvas/{id}/download',
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
            uriTemplate: '/business_model_canvas/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'business_model_canvas:read',
                    'business_model_canvas:item:read'
                ]
            ],
            security: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object.getProject().getTeam())) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getProject().getTeam().getManager() === user)
            '
        ),
        new Put(
            uriTemplate: '/business_model_canvas/{id}',
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
    uriTemplate: '/projects/{id}/business_model_canvas',
    operations: [
        new Get(
            uriTemplate: '/projects/{id}/business_model_canvas/last',
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'business_model_canvas:read',
                    'business_model_canvas:item:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            provider: LastProjectDocumentByProjectGetDataProvider::class
        ),
        new GetCollection(
            uriTemplate: '/projects/{id}/business_model_canvas',
            normalizationContext: [
                'openapi_definition_name' => 'GetCollection',
                'groups' => [
                    'business_model_canvas:read'
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
class BusinessModelCanvas implements TracingAwareInterface, OwnerAwareInterface
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
    #[Groups(['business_model_canvas:read', 'project:item:read'])]
    private ?Uuid $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['business_model_canvas:read', 'business_model_canvas:write'])
    ]
    private ?string $keyPartners = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['business_model_canvas:read', 'business_model_canvas:write'])
    ]
    private ?string $keyActivities = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['business_model_canvas:read', 'business_model_canvas:write'])
    ]
    private ?string $keyResources = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['business_model_canvas:read', 'business_model_canvas:write'])
    ]
    private ?string $valuePropositions = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['business_model_canvas:read', 'business_model_canvas:write'])
    ]
    private ?string $customerRelationships = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['business_model_canvas:read', 'business_model_canvas:write'])
    ]
    private ?string $channels = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['business_model_canvas:read', 'business_model_canvas:write'])
    ]
    private ?string $customerSegments = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['business_model_canvas:read', 'business_model_canvas:write'])
    ]
    private ?string $costStructure = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['business_model_canvas:read', 'business_model_canvas:write'])
    ]
    private ?string $revenueStreams = null;

    /**
     * @var Project|null
     */
    #[ORM\ManyToOne(inversedBy: 'businessModelCanvases')]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Assert\NotBlank,
        Groups(['business_model_canvas:read', 'business_model_canvas:write'])
    ]
    private ?Project $project = null;

    /**
     * @var MediaObject|null
     */
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Groups(['business_model_canvas:item:read'])]
    private ?MediaObject $file = null;

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getKeyPartners(): ?string
    {
        return $this->keyPartners;
    }

    /**
     * @param string $keyPartners
     * @return $this
     */
    public function setKeyPartners(string $keyPartners): static
    {
        $this->keyPartners = $keyPartners;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeyActivities(): ?string
    {
        return $this->keyActivities;
    }

    /**
     * @param string $keyActivities
     * @return $this
     */
    public function setKeyActivities(string $keyActivities): static
    {
        $this->keyActivities = $keyActivities;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeyResources(): ?string
    {
        return $this->keyResources;
    }

    /**
     * @param string $keyResources
     * @return $this
     */
    public function setKeyResources(string $keyResources): static
    {
        $this->keyResources = $keyResources;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValuePropositions(): ?string
    {
        return $this->valuePropositions;
    }

    /**
     * @param string $valuePropositions
     * @return $this
     */
    public function setValuePropositions(string $valuePropositions): static
    {
        $this->valuePropositions = $valuePropositions;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerRelationships(): ?string
    {
        return $this->customerRelationships;
    }

    /**
     * @param string $customerRelationships
     * @return $this
     */
    public function setCustomerRelationships(string $customerRelationships): static
    {
        $this->customerRelationships = $customerRelationships;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getChannels(): ?string
    {
        return $this->channels;
    }

    /**
     * @param string $channels
     * @return $this
     */
    public function setChannels(string $channels): static
    {
        $this->channels = $channels;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerSegments(): ?string
    {
        return $this->customerSegments;
    }

    /**
     * @param string $customerSegments
     * @return $this
     */
    public function setCustomerSegments(string $customerSegments): static
    {
        $this->customerSegments = $customerSegments;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCostStructure(): ?string
    {
        return $this->costStructure;
    }

    /**
     * @param string $costStructure
     * @return $this
     */
    public function setCostStructure(string $costStructure): static
    {
        $this->costStructure = $costStructure;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRevenueStreams(): ?string
    {
        return $this->revenueStreams;
    }

    /**
     * @param string $revenueStreams
     * @return $this
     */
    public function setRevenueStreams(string $revenueStreams): static
    {
        $this->revenueStreams = $revenueStreams;

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