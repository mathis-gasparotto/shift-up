<?php

namespace App\Entity;

use App\Model\TracingAwareInterface;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\GoldenTriangleRepository;
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
     * @var Project|null
     */
    #[ORM\ManyToOne(inversedBy: 'goldenTriangles')]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Assert\NotBlank,
        Groups(['golden_triangle:read', 'golden_triangle:write'])
    ]
    private ?Project $project = null;

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

    public function getBrands(): array
    {
        return $this->brands;
    }

    public function setBrands(array $brands): static
    {
        $this->brands = $brands;

        return $this;
    }
}
