<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Helper\GlobalHelper;
use App\Model\TracingAwareInterface;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\SMARTRepository;
use App\StateProcessor\Project\ProjectDocumentPostDataPersister;
use App\StateProviders\LastProjectDocumentByProjectGetDataProvider;
use App\StateProviders\ProjectDocumentByProjectCollectionDataProvider;
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
    shortName: 'smart',
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
                                        'example' =>'/projects/{id}'
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
        new Get(
            uriTemplate: '/smarts/{id}',
            requirements: [
                'id' => '^[a-z0-9]+(?:-[a-z0-9]+)*$'
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
                'id' => '^[a-z0-9]+(?:-[a-z0-9]+)*$'
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
    shortName: 'smart',
    operations: [new GetCollection()],
    uriVariables: [
        'id' => new Link(
            toProperty: 'project',
            fromClass: Project::class
        )
    ],
    normalizationContext: [
        'groups' => [
            'smart:read'
        ]
    ],
    security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
    provider: ProjectDocumentByProjectCollectionDataProvider::class
)]
#[ApiResource(
    uriTemplate: '/projects/{id}/smarts/last',
    shortName: 'smart',
    operations: [new Get()],
    uriVariables: [
        'id' => new Link(
            toProperty: 'project',
            fromClass: Project::class
        )
    ],
    normalizationContext: [
        'groups' => [
            'smart:read'
        ]
    ],
    security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
    provider: LastProjectDocumentByProjectGetDataProvider::class
)]
class SMART implements TracingAwareInterface
{
    use TracingAwareTrait;

    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['smart:read'])]
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
}
