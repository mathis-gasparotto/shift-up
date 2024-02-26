<?php

namespace App\Entity;

use App\Model\TracingAwareInterface;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\STPRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
#[ORM\Entity(repositoryClass: STPRepository::class)]
class STP implements TracingAwareInterface
{
    use TracingAwareTrait;

    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['stp:read'])]
    private ?Uuid $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['stp:read', 'stp:write'])
    ]
    private ?string $segmentation = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['stp:read', 'stp:write'])
    ]
    private ?string $targeting = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['stp:read', 'stp:write'])
    ]
    private ?string $positioning = null;

    /**
     * @var Project|null
     */
    #[ORM\ManyToOne(inversedBy: 'STPs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['stp:read', 'stp:write'])]
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
    public function getSegmentation(): ?string
    {
        return $this->segmentation;
    }

    /**
     * @param string $segmentation
     * @return $this
     */
    public function setSegmentation(string $segmentation): static
    {
        $this->segmentation = $segmentation;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTargeting(): ?string
    {
        return $this->targeting;
    }

    /**
     * @param string $targeting
     * @return $this
     */
    public function setTargeting(string $targeting): static
    {
        $this->targeting = $targeting;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPositioning(): ?string
    {
        return $this->positioning;
    }

    /**
     * @param string $positioning
     * @return $this
     */
    public function setPositioning(string $positioning): static
    {
        $this->positioning = $positioning;

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
