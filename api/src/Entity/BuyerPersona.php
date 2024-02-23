<?php

namespace App\Entity;

use App\Repository\BuyerPersonaRepository;
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
class BuyerPersona
{
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
    #[Groups(['buyer_persona:read', 'buyer_persona:write'])]
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
