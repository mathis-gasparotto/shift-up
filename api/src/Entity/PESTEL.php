<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Helper\GlobalHelper;
use App\Model\TracingAwareInterface;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\PESTELRepository;
use App\StateProcessor\Project\ProjectDocumentPostDataPersister;
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
#[ORM\Entity(repositoryClass: PESTELRepository::class)]
#[ApiResource(
    shortName: 'pestel',
    operations: [
        new Post(
            uriTemplate: '/pestels',
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
                                    'political' => [
                                        'type' => 'string'
                                    ],
                                    'economic' => [
                                        'type' => 'string'
                                    ],
                                    'social' => [
                                        'type' => 'string'
                                    ],
                                    'technological' => [
                                        'type' => 'string'
                                    ],
                                    'environmental' => [
                                        'type' => 'string'
                                    ],
                                    'legal' => [
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
            uriTemplate: '/pestels/{id}',
            requirements: [
                'id' => '^[a-z0-9]+(?:-[a-z0-9]+)*$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'pestel:read',
                    'pestel:item:read'
                ]
            ],
            security: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object.getProject().getTeam())) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getProject().getTeam().getManager() === user)
            '
        ),
        new Put(
            uriTemplate: '/pestels/{id}',
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
    ]
)]
#[ApiResource(
    uriTemplate: '/projects/{id}/pestels',
    shortName: 'pestel',
    operations: [new GetCollection()],
    uriVariables: [
        'id' => new Link(
            toProperty: 'project',
            fromClass: Project::class
        )
    ],
    normalizationContext: [
        'groups' => [
            'pestel:read'
        ]
    ],
    security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
    provider: ProjectDocumentByProjectCollectionDataProvider::class
)]
class PESTEL implements TracingAwareInterface
{
    use TracingAwareTrait;

    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['pestel:read'])]
    private ?Uuid $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['pestel:read', 'pestel:write'])
    ]
    private ?string $political = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['pestel:read', 'pestel:write'])
    ]
    private ?string $economic = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['pestel:read', 'pestel:write'])
    ]
    private ?string $social = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['pestel:read', 'pestel:write'])
    ]
    private ?string $technological = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['pestel:read', 'pestel:write'])
    ]
    private ?string $environmental = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['pestel:read', 'pestel:write'])
    ]
    private ?string $legal = null;

    /**
     * @var Project|null
     */
    #[ORM\ManyToOne(inversedBy: 'PESTELs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['pestel:read', 'pestel:write'])]
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
    public function getPolitical(): ?string
    {
        return $this->political;
    }

    /**
     * @param string $political
     * @return $this
     */
    public function setPolitical(string $political): static
    {
        $this->political = $political;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEconomic(): ?string
    {
        return $this->economic;
    }

    /**
     * @param string $economic
     * @return $this
     */
    public function setEconomic(string $economic): static
    {
        $this->economic = $economic;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSocial(): ?string
    {
        return $this->social;
    }

    /**
     * @param string $social
     * @return $this
     */
    public function setSocial(string $social): static
    {
        $this->social = $social;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTechnological(): ?string
    {
        return $this->technological;
    }

    /**
     * @param string $technological
     * @return $this
     */
    public function setTechnological(string $technological): static
    {
        $this->technological = $technological;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnvironmental(): ?string
    {
        return $this->environmental;
    }

    /**
     * @param string $environmental
     * @return $this
     */
    public function setEnvironmental(string $environmental): static
    {
        $this->environmental = $environmental;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLegal(): ?string
    {
        return $this->legal;
    }

    /**
     * @param string $legal
     * @return $this
     */
    public function setLegal(string $legal): static
    {
        $this->legal = $legal;

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
