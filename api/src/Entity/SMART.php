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
use App\Controller\Project\ProjectGenerateDocumentController;
use App\Helper\GlobalHelper;
use App\Model\OwnerAwareInterface;
use App\Model\TracingAwareInterface;
use App\Model\Traits\OwnerTrait;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\SMARTRepository;
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
#[ORM\Entity(repositoryClass: SMARTRepository::class)]
#[ApiResource(
    uriTemplate: '/smarts',
    operations: [
        new Post(
            uriTemplate: '/smarts',
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
                                    'keySpecific' => [
                                        'type' => 'string'
                                    ],
                                    'keyMeasurable' => [
                                        'type' => 'string'
                                    ],
                                    'keyAchievable' => [
                                        'type' => 'string'
                                    ],
                                    'keyTimed' => [
                                        'type' => 'string'
                                    ],
                                    'keyRelevant' => [
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
            uriTemplate: '/projects/{id}/smarts/generate',
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
            uriTemplate: '/projects/{id}/smarts/last/download',
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
        new Get(
            uriTemplate: '/smarts/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'smart:read',
                    'smart:item:read'
                ]
            ],
            security: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object.getProject().getTeam())) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getProject().getTeam().getManager() === user)
            '
        ),
        new Put(
            uriTemplate: '/smarts/{id}',
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
    uriTemplate: '/projects/{id}/smarts',
    operations: [
        new Get(
            uriTemplate: '/projects/{id}/smarts/last',
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'smart:read',
                    'smart:item:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            provider: LastProjectDocumentByProjectGetDataProvider::class
        ),
        new GetCollection(
            uriTemplate: '/projects/{id}/smarts',
            normalizationContext: [
                'openapi_definition_name' => 'GetCollection',
                'groups' => [
                    'smart:read'
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
class SMART implements TracingAwareInterface, OwnerAwareInterface
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
    #[Groups(['smart:read', 'project:item:read'])]
    private ?Uuid $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['smart:read', 'smart:write'])
    ]
    private ?string $keySpecific = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['smart:read', 'smart:write'])
    ]
    private ?string $keyMeasurable = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['smart:read', 'smart:write'])
    ]
    private ?string $keyAchievable = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['smart:read', 'smart:write'])
    ]
    private ?string $keyRelevant = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['smart:read', 'smart:write'])
    ]
    private ?string $keyTimed = null;

    /**
     * @var Project|null
     */
    #[ORM\ManyToOne(inversedBy: 'SMARTs')]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Assert\NotBlank,
        Groups(['smart:read', 'smart:write'])
    ]
    private ?Project $project = null;

    /**
     * @var MediaObject|null
     */
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Groups(['smart:item:read'])]
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
    public function getKeySpecific(): ?string
    {
        return $this->keySpecific;
    }

    /**
     * @param string $keySpecific
     * @return $this
     */
    public function setKeySpecific(string $keySpecific): static
    {
        $this->keySpecific = $keySpecific;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeyMeasurable(): ?string
    {
        return $this->keyMeasurable;
    }

    /**
     * @param string $keyMeasurable
     * @return $this
     */
    public function setKeyMeasurable(string $keyMeasurable): static
    {
        $this->keyMeasurable = $keyMeasurable;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeyAchievable(): ?string
    {
        return $this->keyAchievable;
    }

    /**
     * @param string $keyAchievable
     * @return $this
     */
    public function setKeyAchievable(string $keyAchievable): static
    {
        $this->keyAchievable = $keyAchievable;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeyRelevant(): ?string
    {
        return $this->keyRelevant;
    }

    /**
     * @param string $keyRelevant
     * @return $this
     */
    public function setKeyRelevant(string $keyRelevant): static
    {
        $this->keyRelevant = $keyRelevant;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeyTimed(): ?string
    {
        return $this->keyTimed;
    }

    /**
     * @param string $keyTimed
     * @return $this
     */
    public function setKeyTimed(string $keyTimed): static
    {
        $this->keyTimed = $keyTimed;

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
