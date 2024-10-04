<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Helper\GlobalHelper;
use App\Helper\SubscriptionHelper;
use App\Model\OwnerAwareInterface;
use App\Model\TracingAwareInterface;
use App\Model\Traits\OwnerTrait;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\SubscriptionRepository;
use App\StateProviders\SlugEntityProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 *
 */
#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/subscriptions',
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'label' => [
                                        'type' => 'string'
                                    ],
                                    'description' => [
                                        'type' => 'string'
                                    ],
                                    'price' => [
                                        'type' => 'integer'
                                    ],
                                    'recurrence' => [
                                        'type' => 'string'
                                    ],
                                    'stripeProductId' => [
                                        'type' => 'string'
                                    ],
                                    'stripePriceId' => [
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
            security: 'is_granted("' . GlobalHelper::ROLE_ADMIN . '")'
        ),
        new GetCollection(
            uriTemplate: '/subscriptions',
            normalizationContext: [
                'openapi_definition_name' => 'GetCollection',
                'groups' => [
                    'subscription:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::PUBLIC_ACCESS . '")'
        ),
        new Get(
            uriTemplate: '/subscriptions/{slug}',
            requirements: [
                'slug' => '^[a-z0-9]+(?:-[a-z0-9]+)*$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'subscription:read',
                    'subscription:item:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::PUBLIC_ACCESS . '")',
            provider: SlugEntityProvider::class
        ),
        new Put(
            uriTemplate: '/subscriptions/{slug}',
            requirements: [
                'slug' => '^[a-z0-9]+(?:-[a-z0-9]+)*$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'PutItem'
            ],
            securityPostDenormalize: 'is_granted("' . GlobalHelper::ROLE_ADMIN . '")',
            provider: SlugEntityProvider::class
        ),
        new Delete(
            uriTemplate: '/subscriptions/{slug}',
            requirements: [
                'slug' => '^[a-z0-9]+(?:-[a-z0-9]+)*$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'DeleteItem'
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_ADMIN . '")',
            provider: SlugEntityProvider::class
        )
    ]
)]
class Subscription implements TracingAwareInterface, OwnerAwareInterface
{
    use TracingAwareTrait;
    use OwnerTrait;

    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['subscription:read'])]
    #[ApiProperty(identifier: false)]
    private ?Uuid $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Length(min: 3, max: 255),
        Groups(['subscription:read', 'subscription:write'])
    ]
    private ?string $label = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['label'])]
    #[Groups(['subscription:read'])]
    #[ApiProperty(identifier: true)]
    private ?string $slug = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[
        Assert\NotBlank,
        Groups(['subscription:read', 'subscription:write'])
    ]
    private ?string $description = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    #[
        Assert\NotNull,
        Assert\Type(type: 'integer'),
        Groups(['subscription:read', 'subscription:write'])
    ]
    private ?int $price = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Choice(
            choices: SubscriptionHelper::SUBSCRIPTION_RECURRENCES,
            message: 'Invalid recurrence, valid recurrences are: {{ choices }}'
        ),
        Groups(['subscription:read', 'subscription:write'])
    ]
    private ?string $recurrence = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'subscription', targetEntity: Team::class)]
    private Collection $teams;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Groups(['subscription:read', 'subscription:write'])
    ]
    private ?string $stripeProductId = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Groups(['subscription:read', 'subscription:write'])
    ]
    private ?string $stripePriceId = null;

    /**
     *
     */
    public function __construct()
    {
        $this->teams = new ArrayCollection();
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
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return $this
     */
    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRecurrence(): ?string
    {
        return $this->recurrence;
    }

    /**
     * @param string $recurrence
     * @return $this
     */
    public function setRecurrence(string $recurrence): static
    {
        $this->recurrence = $recurrence;

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    /**
     * @param Team $team
     * @return $this
     */
    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->setSubscription($this);
        }

        return $this;
    }

    /**
     * @param Team $team
     * @return $this
     */
    public function removeTeam(Team $team): static
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getSubscription() === $this) {
                $team->setSubscription(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStripeProductId(): ?string
    {
        return $this->stripeProductId;
    }

    /**
     * @param string $stripeProductId
     * @return $this
     */
    public function setStripeProductId(string $stripeProductId): static
    {
        $this->stripeProductId = $stripeProductId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStripePriceId(): ?string
    {
        return $this->stripePriceId;
    }

    /**
     * @param string $stripePriceId
     * @return $this
     */
    public function setStripePriceId(string $stripePriceId): static
    {
        $this->stripePriceId = $stripePriceId;

        return $this;
    }
}
