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
                                    'monthPrice' => [
                                        'type' => 'integer'
                                    ],
                                    'yearPrice' => [
                                        'type' => 'integer'
                                    ],
                                    'stripeProductId' => [
                                        'type' => 'string'
                                    ],
                                    'stripeMonthPriceId' => [
                                        'type' => 'string'
                                    ],
                                    'stripeYearPriceId' => [
                                        'type' => 'string'
                                    ],
                                    'advantages' => [
                                        'type' => 'array'
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
    #[Groups(['subscription:read', 'team:read'])]
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
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[
        Groups(['subscription:read', 'subscription:write'])
    ]
    private ?string $stripeProductId = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'subscription', targetEntity: SubscriptionPrice::class, orphanRemoval: true)]
    #[
        Groups(['subscription:read', 'subscription:write'])
    ]
    private Collection $prices;

    /**
     * @var array|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['subscription:read', 'subscription:write'])]
    private ?array $advantages = null;

    /**
     *
     */
    public function __construct()
    {
        $this->prices = new ArrayCollection();
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
     * @return Collection<int, SubscriptionPrice>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    /**
     * @param SubscriptionPrice $price
     * @return $this
     */
    public function addPrice(SubscriptionPrice $price): static
    {
        if (!$this->prices->contains($price)) {
            $this->prices->add($price);
            $price->setSubscription($this);
        }

        return $this;
    }

    /**
     * @param SubscriptionPrice $price
     * @return $this
     */
    public function removePrice(SubscriptionPrice $price): static
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getSubscription() === $this) {
                $price->setSubscription(null);
            }
        }

        return $this;
    }

    /**
     * @return array|null
     */
    public function getAdvantages(): ?array
    {
        return $this->advantages;
    }

    /**
     * @param array|null $advantages
     * @return $this
     */
    public function setAdvantages(?array $advantages): static
    {
        $this->advantages = $advantages;

        return $this;
    }
}
