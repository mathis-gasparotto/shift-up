<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Team\TeamChoiceSubscriptionController;
use App\DTO\TeamChoiceSubscriptionDto;
use App\Helper\GlobalHelper;
use App\Helper\TeamHelper;
use App\Model\ManagerAwareInterface;
use App\Model\TracingAwareInterface;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\TeamRepository;
use App\StateProviders\TeamMeCollectionDataProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/teams',
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'name' => [
                                        'type' => 'string'
                                    ],
                                    'description' => [
                                        'type' => 'string'
                                    ],
                                    'billingEmail' => [
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
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")'
        ),
        new GetCollection(
            uriTemplate: '/teams',
            normalizationContext: [
                'openapi_definition_name' => 'GetCollection',
                'groups' => [
                    'team:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            provider: TeamMeCollectionDataProvider::class
        ),
        new Get(
            uriTemplate: '/teams/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'teams:read',
                    'teams:item:read'
                ]
            ],
            security: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and user.isInTeam(object)) or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getManager() === user)
            '
        ),
        new Put(
            uriTemplate: '/teams/{id}',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'PutItem'
            ],
            securityPostDenormalize: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getManager() === user)
            '
        ),
        new Delete(
            normalizationContext: [
                'openapi_definition_name' => 'DeleteItem'
            ],
            security: '
                is_granted("' . GlobalHelper::ROLE_ADMIN . '") or
                (is_granted("' . GlobalHelper::ROLE_USER . '") and object.getManager() === user)
            '
        ),
        new Post(
            uriTemplate: '/teams/{id}/choice_subscription',
            requirements: [
                'id' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'
            ],
            controller: TeamChoiceSubscriptionController::class,
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'subscription' => [
                                        'type' => 'string'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            normalizationContext: [
                'openapi_definition_name' => 'ChoiceTeamSubscription'
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")'
        ),
    ]
)]
class Team implements ManagerAwareInterface, TracingAwareInterface
{
    use TracingAwareTrait;

    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['team:read', 'project:read'])]
    private ?Uuid $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['team:admin:read'])]
    private ?string $stripeCustomerId = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['team:admin:read'])]
    private ?string $stripeSubscriptionId = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Assert\Choice(
            choices: TeamHelper::STATUS,
            message: 'Invalid status, valid status are: {{ choices }}'
        ),
        Groups(['team:read'])
    ]
    private ?string $status = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Groups(['team:read', 'team:write', 'project:read'])]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['team:read', 'team:write'])]
    private ?string $description = null;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['team:read'])]
    private ?\DateTime $subscriptionEndAt = null;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'teamsManaged')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['team:read', 'project:read'])]
    private ?User $manager = null;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'teams')]
    #[Groups(['team:read'])]
    private Collection $users;

    /**
     * @var Collection|ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Project::class, orphanRemoval: true)]
    #[Groups(['team:read'])]
    private Collection $projects;

    /**
     * @var Subscription|null
     */
    #[ORM\ManyToOne(inversedBy: 'teams')]
    #[Groups(['team:read'])]
    private ?Subscription $subscription = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Email,
        Groups(['team:read', 'team:write']),
    ]
    private ?string $billingEmail = null;

    /**
     *
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->status = TeamHelper::STATUS_ACTIVE;
        $this->projects = new ArrayCollection();
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
    public function getStripeCustomerId(): ?string
    {
        return $this->stripeCustomerId;
    }

    /**
     * @param string|null $stripeCustomerId
     * @return $this
     */
    public function setStripeCustomerId(?string $stripeCustomerId): static
    {
        $this->stripeCustomerId = $stripeCustomerId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStripeSubscriptionId(): ?string
    {
        return $this->stripeSubscriptionId;
    }

    /**
     * @param string|null $stripeSubscriptionId
     * @return $this
     */
    public function setStripeSubscriptionId(?string $stripeSubscriptionId): static
    {
        $this->stripeSubscriptionId = $stripeSubscriptionId;

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
     * @return \DateTime|null
     */
    public function getSubscriptionEndAt(): ?\DateTime
    {
        return $this->subscriptionEndAt;
    }

    /**
     * @param \DateTime|null $subscriptionEndAt
     * @return $this
     */
    public function setSubscriptionEndAt(?\DateTime $subscriptionEndAt): static
    {
        $this->subscriptionEndAt = $subscriptionEndAt;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getManager(): ?User
    {
        return $this->manager;
    }

    /**
     * @param User|null $manager
     * @return $this
     */
    public function setManager(?User $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addTeam($this);
        }

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeTeam($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    /**
     * @param Project $project
     * @return $this
     */
    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setTeam($this);
        }

        return $this;
    }

    /**
     * @param Project $project
     * @return $this
     */
    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getTeam() === $this) {
                $project->setTeam(null);
            }
        }

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
    public function getBillingEmail(): ?string
    {
        return $this->billingEmail;
    }

    /**
     * @param string $billingEmail
     * @return $this
     */
    public function setBillingEmail(string $billingEmail): static
    {
        $this->billingEmail = $billingEmail;

        return $this;
    }
}
