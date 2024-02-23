<?php

namespace App\Entity;

use App\Model\TracingAwareInterface;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\SMARTRepository;
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
    #[Groups(['smart:read', 'smart:write'])]
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
