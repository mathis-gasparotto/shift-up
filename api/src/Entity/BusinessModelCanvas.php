<?php

namespace App\Entity;

use App\Model\TracingAwareInterface;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\BusinessModelCanvasRepository;
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
class BusinessModelCanvas implements TracingAwareInterface
{
    use TracingAwareTrait;
    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['business_model_canvas:read'])]
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
    #[Groups(['business_model_canvas:read', 'business_model_canvas:write'])]
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
}
