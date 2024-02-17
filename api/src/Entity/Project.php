<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Helper\GlobalHelper;
use App\Helper\ProjectHelper;
use App\Repository\ProjectRepository;
use App\StateProcessor\Project\ProjectPostDataPersister;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/projects',
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'team' => [
                                        'type' => 'string',
                                        'example' =>'/teams/{id}'
                                    ],
                                    'name' => [
                                        'type' => 'string'
                                    ],
                                    'description' => [
                                        'type' => 'string'
                                    ],
                                    'subject' => [
                                        'type' => 'string'
                                    ],
                                    'sellingObject' => [
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
            processor: ProjectPostDataPersister::class
        ),
    ]
)]
class Project
{
    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['project:read'])]
    private ?Uuid $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups(['project:read', 'project:write'])]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['project:read', 'project:write'])]
    private ?string $description = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups(['project:read', 'project:write'])]
    private ?string $subject = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Assert\Choice(
            choices: ProjectHelper::STATUS,
            message: 'Invalid status, valid status are: {{ choices }}'
        ),
        Groups(['project:read'])
    ]
    private ?string $status = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['project:read', 'project:write'])]
    private ?string $sellingObject = null;

    /**
     * @var Team|null
     */
    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['project:read', 'project:write'])]
    private ?Team $team = null;

    /**
     *
     */
    public function __construct()
    {
        $this->status = ProjectHelper::STATUS_ACTIVE;
    }

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSellingObject(): ?string
    {
        return $this->sellingObject;
    }

    /**
     * @param string|null $sellingObject
     * @return $this
     */
    public function setSellingObject(?string $sellingObject): static
    {
        $this->sellingObject = $sellingObject;

        return $this;
    }

    /**
     * @return Team|null
     */
    public function getTeam(): ?Team
    {
        return $this->team;
    }

    /**
     * @param Team|null $team
     * @return $this
     */
    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }
}
