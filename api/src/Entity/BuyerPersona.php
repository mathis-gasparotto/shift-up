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
use App\Repository\BuyerPersonaRepository;
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
#[ORM\Entity(repositoryClass: BuyerPersonaRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/buyer_personas',
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
                                    'personalInfo' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string'
                                        ]
                                    ],
                                    'goalsChallenges' => [
                                        'type' => 'string'
                                    ],
                                    'communicationChannels' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string'
                                        ]
                                    ],
                                    'valuesFears' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string'
                                        ]
                                    ],
                                    'motivation' => [
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
            security: 'is_granted("' . GlobalHelper::ROLE_ADMIN . '")',
            processor: ProjectDocumentPostDataPersister::class
        ),
        new Post(
            uriTemplate: '/projects/{id}/buyer_personas/generate',
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
            uriTemplate: '/projects/{id}/buyer_personas/last/download',
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
            uriTemplate: '/buyer_personas/{id}/download',
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
            uriTemplate: '/buyer_personas/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'buyer_persona:read',
                    'buyer_persona:item:read'
                ]
            ],
            security: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object.getProject().getTeam())) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getProject().getTeam().getManager() === user)
            '
        ),
        new Put(
            uriTemplate: '/buyer_personas/{id}',
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
    uriTemplate: '/projects/{id}/buyer_personas',
    operations: [
        new Get(
            uriTemplate: '/projects/{id}/buyer_personas/last',
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'buyer_persona:read',
                    'buyer_persona:item:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            provider: LastProjectDocumentByProjectGetDataProvider::class
        ),
        new GetCollection(
            uriTemplate: '/projects/{id}/buyer_personas',
            normalizationContext: [
                'openapi_definition_name' => 'GetCollection',
                'groups' => [
                    'buyer_persona:read'
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
class BuyerPersona implements TracingAwareInterface, OwnerAwareInterface
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
    #[Groups(['buyer_persona:read', 'project:item:read'])]
    private ?Uuid $id = null;

    /**
     * @var array|null
     */
    #[ORM\Column(type: Types::JSON)]
    #[
        Assert\NotBlank,
        Groups(['buyer_persona:read', 'buyer_persona:write'])
    ]
    private ?array $personalInfo = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['buyer_persona:read', 'buyer_persona:write'])
    ]
    private ?string $goalsChallenges = null;

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
        Groups(['buyer_persona:read', 'buyer_persona:write'])
    ]
    private ?array $communicationChannels = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['buyer_persona:read', 'buyer_persona:write'])
    ]
    private ?string $valuesFears = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['buyer_persona:read', 'buyer_persona:write'])
    ]
    private ?string $motivation = null;

    /**
     * @var Project|null
     */
    #[ORM\ManyToOne(inversedBy: 'buyerPersonas')]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Assert\NotBlank,
        Groups(['buyer_persona:read', 'buyer_persona:write'])
    ]
    private ?Project $project = null;

    /**
     * @var MediaObject|null
     */
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Groups(['buyer_persona:item:read'])]
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
    public function getPersonalInfo(): ?array
    {
        return $this->personalInfo;
    }

    /**
     * @param array $personalInfo
     * @return $this
     */
    public function setPersonalInfo(array $personalInfo): static
    {
        $this->personalInfo = $personalInfo;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGoalsChallenges(): ?string
    {
        return $this->goalsChallenges;
    }

    /**
     * @param string $goalsChallenges
     * @return $this
     */
    public function setGoalsChallenges(string $goalsChallenges): static
    {
        $this->goalsChallenges = $goalsChallenges;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getCommunicationChannels(): ?array
    {
        return $this->communicationChannels;
    }

    /**
     * @param array $communicationChannels
     * @return $this
     */
    public function setCommunicationChannels(array $communicationChannels): static
    {
        $this->communicationChannels = $communicationChannels;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValuesFears(): ?string
    {
        return $this->valuesFears;
    }

    /**
     * @param string $valuesFears
     * @return $this
     */
    public function setValuesFears(string $valuesFears): static
    {
        $this->valuesFears = $valuesFears;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    /**
     * @param string $motivation
     * @return $this
     */
    public function setMotivation(string $motivation): static
    {
        $this->motivation = $motivation;

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