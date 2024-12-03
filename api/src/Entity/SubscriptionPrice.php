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
use App\Repository\SubscriptionPriceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
#[ORM\Entity(repositoryClass: SubscriptionPriceRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/subscription_prices',
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'price' => [
                                        'type' => 'integer'
                                    ],
                                    'subscription' => [
                                        'type' => 'string'
                                    ],
                                    'recurrence' => [
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
            uriTemplate: '/subscription_prices',
            normalizationContext: [
                'openapi_definition_name' => 'GetCollection',
                'groups' => [
                    'subscription_price:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_ADMIN . '")'
        ),
        new Get(
            uriTemplate: '/subscription_prices/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'subscription_price:read',
                    'subscription_price:item:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_ADMIN . '")',
        ),
        new Put(
            uriTemplate: '/subscription_prices/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'PutItem'
            ],
            securityPostDenormalize: 'is_granted("' . GlobalHelper::ROLE_ADMIN . '")',
        ),
        new Delete(
            uriTemplate: '/subscription_prices/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'DeleteItem'
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_ADMIN . '")',
        )
    ]
)]
class SubscriptionPrice implements TracingAwareInterface, OwnerAwareInterface
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
    #[Groups(['subscription:read', 'subscription_price:read', 'team:read'])]
    #[ApiProperty(identifier: false)]
    private ?Uuid $id = null;

    /**
     * @var int|null
     */
    #[ORM\Column(nullable: false, options: ['default' => 0])]
    #[
        Assert\NotBlank,
        Assert\Type(type: 'integer'),
        Groups(['subscription:read', 'subscription_price:read', 'subscription_price:write'])
    ]
    private ?int $price = null;

    /**
     * @var Subscription|null
     */
    #[ORM\ManyToOne(inversedBy: 'prices')]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Groups(['subscription_price:read', 'subscription_price:write', 'team:read']),
    ]
    private ?Subscription $subscription = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Groups(['subscription:read', 'subscription_price:read', 'subscription_price:write']),
        Assert\NotBlank,
        Assert\Choice(
            choices: SubscriptionHelper::SUBSCRIPTION_RECURRENCES,
            message: 'Invalid recurrence, valid recurrences are: {{ choices }}'
        ),
    ]
    private ?string $recurrence = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[
        Groups(['subscription:read', 'subscription_price:read', 'subscription_price:write'])
    ]
    private ?string $stripePriceId = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'subscriptionPrice', targetEntity: Team::class)]
    private Collection $teams;

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
     * @return Subscription|null
     */
    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    /**
     * @param Subscription|null $subscription
     * @return $this
     */
    public function setSubscription(?Subscription $subscription): static
    {
        $this->subscription = $subscription;

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
     * @return string|null
     */
    public function getStripePriceId(): ?string
    {
        return $this->stripePriceId;
    }

    /**
     * @param string|null $stripePriceId
     * @return $this
     */
    public function setStripePriceId(?string $stripePriceId): static
    {
        $this->stripePriceId = $stripePriceId;

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
}
