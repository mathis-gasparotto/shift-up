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
use App\Repository\SWOTRepository;
use App\StateProcessor\Project\ProjectDocumentPostDataPersister;
use App\Controller\Project\ProjectGenerateDocumentController;
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
                                        'example' =>'/projects/{id}'
                                    ],
                                    'strengths' => [
                                        'type' => 'string'
                                    ],
                                    'weaknesses' => [
                                        'type' => 'string'
                                    ],
                                    'opportunities' => [
                                        'type' => 'string'
                                    ],
                                    'threats' => [
                                        'type' => 'string'
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
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
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
    operations: [new GetCollection()],
    uriVariables: [
        'id' => new Link(
            toProperty: 'project',
            fromClass: Project::class
        )
    ],
    normalizationContext: [
        'groups' => [
            'swot:read'
        ]
    ],
    security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
    provider: ProjectDocumentByProjectCollectionDataProvider::class
)]
#[ApiResource(
    uriTemplate: '/projects/{id}/swots/last',
    operations: [new Get()],
    uriVariables: [
        'id' => new Link(
            toProperty: 'project',
            fromClass: Project::class
        )
    ],
    normalizationContext: [
        'groups' => [
            'swot:read'
        ]
    ],
    security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
    provider: LastProjectDocumentByProjectGetDataProvider::class
)]
class SWOT implements TracingAwareInterface
{
    use TracingAwareTrait;

    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['swot:read'])]
    private ?Uuid $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['swot:read', 'swot:write'])
    ]
    private ?string $strengths = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['swot:read', 'swot:write'])
    ]
    private ?string $weaknesses = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['swot:read', 'swot:write'])
    ]
    private ?string $opportunities = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['swot:read', 'swot:write'])
    ]
    private ?string $threats = null;

    /**
     * @var Project|null
     */
    #[ORM\ManyToOne(inversedBy: 'SWOTs')]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Assert\NotBlank,
        Groups(['swot:read', 'swot:write'])
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
    public function getStrengths(): ?string
    {
        return $this->strengths;
    }

    /**
     * @param string $strengths
     * @return $this
     */
    public function setStrengths(string $strengths): static
    {
        $this->strengths = $strengths;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWeaknesses(): ?string
    {
        return $this->weaknesses;
    }

    /**
     * @param string $weaknesses
     * @return $this
     */
    public function setWeaknesses(string $weaknesses): static
    {
        $this->weaknesses = $weaknesses;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOpportunities(): ?string
    {
        return $this->opportunities;
    }

    /**
     * @param string $opportunities
     * @return $this
     */
    public function setOpportunities(string $opportunities): static
    {
        $this->opportunities = $opportunities;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getThreats(): ?string
    {
        return $this->threats;
    }

    /**
     * @param string $threats
     * @return $this
     */
    public function setThreats(string $threats): static
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
}
