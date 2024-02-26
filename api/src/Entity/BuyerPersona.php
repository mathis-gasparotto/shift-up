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
use App\Repository\BuyerPersonaRepository;
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
                                        'example' =>'/projects/{id}'
                                    ],
                                    'personalInfo' => [
                                        'type' => 'string'
                                    ],
                                    'professionalInfo' => [
                                        'type' => 'string'
                                    ],
                                    'goalsChallenges' => [
                                        'type' => 'string'
                                    ],
                                    'communicationChannels' => [
                                        'type' => 'string'
                                    ],
                                    'valuesFears' => [
                                        'type' => 'string'
                                    ],
                                    'negativeInfo' => [
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
            uriTemplate: '/buyer_personas/{id}',
            requirements: [
                'id' => '^[a-z0-9]+(?:-[a-z0-9]+)*$'
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
    uriTemplate: '/projects/{id}/buyer_personas',
    operations: [new GetCollection()],
    uriVariables: [
        'id' => new Link(
            toProperty: 'project',
            fromClass: Project::class
        )
    ],
    normalizationContext: [
        'groups' => [
            'buyer_persona:read'
        ]
    ],
    security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
    provider: ProjectDocumentByProjectCollectionDataProvider::class
)]
class BuyerPersona implements TracingAwareInterface
{
    use TracingAwareTrait;

    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['buyer_persona:read'])]
    private ?Uuid $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['buyer_persona:read', 'buyer_persona:write'])
    ]
    private ?string $personalInfo = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['buyer_persona:read', 'buyer_persona:write'])
    ]
    private ?string $professionalInfo = null;

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
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['buyer_persona:read', 'buyer_persona:write'])
    ]
    private ?string $communicationChannels = null;

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
    private ?string $negativeInfo = null;

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
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getPersonalInfo(): ?string
    {
        return $this->personalInfo;
    }

    /**
     * @param string $personalInfo
     * @return $this
     */
    public function setPersonalInfo(string $personalInfo): static
    {
        $this->personalInfo = $personalInfo;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProfessionalInfo(): ?string
    {
        return $this->professionalInfo;
    }

    /**
     * @param string $professionalInfo
     * @return $this
     */
    public function setProfessionalInfo(string $professionalInfo): static
    {
        $this->professionalInfo = $professionalInfo;

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
     * @return string|null
     */
    public function getCommunicationChannels(): ?string
    {
        return $this->communicationChannels;
    }

    /**
     * @param string $communicationChannels
     * @return $this
     */
    public function setCommunicationChannels(string $communicationChannels): static
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
    public function getNegativeInfo(): ?string
    {
        return $this->negativeInfo;
    }

    /**
     * @param string $negativeInfo
     * @return $this
     */
    public function setNegativeInfo(string $negativeInfo): static
    {
        $this->negativeInfo = $negativeInfo;

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
