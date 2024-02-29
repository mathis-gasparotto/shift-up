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
use App\Repository\GoldenTriangleRepository;
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
#[ORM\Entity(repositoryClass: GoldenTriangleRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/golden_triangles',
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
                                    'topLabel' => [
                                        'type' => 'string'
                                    ],
                                    'leftLabel' => [
                                        'type' => 'string'
                                    ],
                                    'rightLabel' => [
                                        'type' => 'string'
                                    ],
                                    'brands' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'name' => [
                                                    'type' => 'string'
                                                ],
                                                'topPosition' => [
                                                    'type' => 'number',
                                                    'format' => 'float'
                                                ],
                                                'leftPosition' => [
                                                    'type' => 'number',
                                                    'format' => 'float'
                                                ],
                                                'rightPosition' => [
                                                    'type' => 'number',
                                                    'format' => 'float'
                                                ],
                                            ],
                                        ],
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
            uriTemplate: '/golden_triangles/{id}',
            requirements: [
                'id' => '^[a-z0-9]+(?:-[a-z0-9]+)*$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'golden_triangle:read',
                    'golden_triangle:item:read'
                ]
            ],
            security: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object.getProject().getTeam())) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getProject().getTeam().getManager() === user)
            '
        ),
        new Put(
            uriTemplate: '/golden_triangles/{id}',
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
    uriTemplate: '/projects/{id}/golden_triangles',
    operations: [new GetCollection()],
    uriVariables: [
        'id' => new Link(
            toProperty: 'project',
            fromClass: Project::class
        )
    ],
    normalizationContext: [
        'groups' => [
            'golden_triangle:read'
        ]
    ],
    security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
    provider: ProjectDocumentByProjectCollectionDataProvider::class
)]
#[ApiResource(
    uriTemplate: '/projects/{id}/golden_triangles/last',
    operations: [new Get()],
    uriVariables: [
        'id' => new Link(
            toProperty: 'project',
            fromClass: Project::class
        )
    ],
    normalizationContext: [
        'groups' => [
            'golden_triangle:read'
        ]
    ],
    security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
    provider: LastProjectDocumentByProjectGetDataProvider::class
)]
class GoldenTriangle implements TracingAwareInterface
{
    use TracingAwareTrait;

    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['golden_triangle:read'])]
    private ?Uuid $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Length(max: 255),
        Groups(['golden_triangle:read', 'golden_triangle:write'])
    ]
    private ?string $topLabel = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Length(max: 255),
        Groups(['golden_triangle:read', 'golden_triangle:write'])
    ]
    private ?string $leftLabel = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Length(max: 255),
        Groups(['golden_triangle:read', 'golden_triangle:write'])
    ]
    private ?string $rightLabel = null;

    /**
     * @var array
     */
    #[ORM\Column(type: Types::JSON)]
    #[
        Assert\All([
            new Assert\Collection([
                'name' => new Assert\NotBlank(),
                'topPosition' => [
                    new Assert\NotBlank(),
                    new Assert\Type(type: 'float'),
                    new Assert\Range(min: 0, max: 100)
                ],
                'leftPosition' => [
                    new Assert\NotBlank(),
                    new Assert\Type(type: 'float'),
                    new Assert\Range(min: 0, max: 100)
                ],
                'rightPosition' => [
                    new Assert\NotBlank(),
                    new Assert\Type(type: 'float'),
                    new Assert\Range(min: 0, max: 100)
                ]
            ])
        ]),
        Groups(['golden_triangle:read', 'golden_triangle:write'])
    ]
    private array $brands = [];

    /**
     * @var Project|null
     */
    #[ORM\ManyToOne(inversedBy: 'goldenTriangles')]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Assert\NotBlank,
        Groups(['golden_triangle:read', 'golden_triangle:write'])
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
    public function getTopLabel(): ?string
    {
        return $this->topLabel;
    }

    /**
     * @param string $topLabel
     * @return $this
     */
    public function setTopLabel(string $topLabel): static
    {
        $this->topLabel = $topLabel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLeftLabel(): ?string
    {
        return $this->leftLabel;
    }

    /**
     * @param string $leftLabel
     * @return $this
     */
    public function setLeftLabel(string $leftLabel): static
    {
        $this->leftLabel = $leftLabel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRightLabel(): ?string
    {
        return $this->rightLabel;
    }

    /**
     * @param string $rightLabel
     * @return $this
     */
    public function setRightLabel(string $rightLabel): static
    {
        $this->rightLabel = $rightLabel;

        return $this;
    }

    /**
     * @return array
     */
    public function getBrands(): array
    {
        return $this->brands;
    }

    /**
     * @param array $brands
     * @return $this
     */
    public function setBrands(array $brands): static
    {
        $this->brands = $brands;

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
