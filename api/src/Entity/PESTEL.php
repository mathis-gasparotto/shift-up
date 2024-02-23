<?php

namespace App\Entity;

use App\Model\TracingAwareInterface;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\PESTELRepository;
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
