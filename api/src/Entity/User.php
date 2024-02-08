<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Controller\User\GetMeAction;
use App\Controller\User\ResetPasswordController;
use App\Controller\User\SendResetPasswordController;
use App\Controller\User\VerifyEmailController;
use App\Helper\GlobalHelper;
use App\Model\TracingAwareInterface;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\UserRepository;
use App\StateProcessor\User\UserPostDataPersister;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ["email"])]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/register',
            normalizationContext: [
                'openapi_definition_name' => 'PostCollection',
                'groups' => [
                    'user:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::PUBLIC_ACCESS . '")',
            validationContext: [
                'groups' => [
                    'Default',
                    'register'
                ]
            ],
            processor: UserPostDataPersister::class
        ),
        new Get(
            uriTemplate: '/users/me',
            status: 200,
            controller: GetMeAction::class,
            normalizationContext: [
                'openapi_definition_name' => 'UserMeItem',
                'groups' => [
                    'user:read',
                    'user:item:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            read: false,
            name: 'get_user_me'
        ),
        new Get(
            uriTemplate: '/verify_email_register/{token}',
            requirements: [
                'token' => '.+'
            ],
            status: 200,
            controller: VerifyEmailController::class,
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'email' => [
                                        'type' => 'string'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            normalizationContext: [
                'openapi_definition_name' => 'SendResetPasswordCollection'
            ],
            name: 'app_verify_email_register'
        ),
        new Post(
            uriTemplate: '/send_reset_password',
            status: 200,
            controller: SendResetPasswordController::class,
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'email' => [
                                        'type' => 'string'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            normalizationContext: [
                'openapi_definition_name' => 'SendResetPasswordCollection'
            ],
            name: 'app_send_reset_password'
        ),
        new Post(
            uriTemplate: '/reset_password/{token}',
            requirements: [
                'token' => '.+'
            ],
            status: 200,
            controller: ResetPasswordController::class,
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'application/ld+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'password' => [
                                        'type' => 'string'
                                    ],
                                    'confirmPassword' => [
                                        'type' => 'string'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            normalizationContext: [
                'openapi_definition_name' => 'ResetPasswordCollection'
            ],
            read: false,
            name: 'app_reset_password'
        )

    ]
)]

class User implements UserInterface, PasswordAuthenticatedUserInterface, TracingAwareInterface
{
    use TracingAwareTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['user:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(
        ['user:read', 'user:write']),
        Assert\NotBlank(groups: ['register'])
    ]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups(
        ['user:read', 'user:write']),
        Assert\NotBlank(groups: ['register'])
    ]
    private ?string $firstName = null;

    #[ORM\Column(length: 60, nullable: true)]
    #[Groups(
        ['user:read', 'user:write']),
        Assert\NotBlank(groups: ['register'])
    ]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    #[
        Groups(['user:read', 'user:write']),
        Assert\NotBlank(groups: ['register'])
    ]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank(groups: ['register']),
        Assert\Length(min: 8, max: 32),
        Assert\Regex(pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>])(?!.*\s).*$/', message: 'Password must contain at least one lowercase letter, one uppercase letter, one number and one special character')
    ]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['user:admin:read'])]
    private ?\DateTimeInterface $lastLoginAt = null;

    #[ORM\Column]
    #[Groups(['user:admin:read', 'user:admin:write'])]
    private array $roles = [];

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $confirmationToken;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resetPasswordToken = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type("\DateTimeInterface")]
    private ?\DateTimeInterface $resetPasswordAt = null;

    /**
     *
     */
    public function __construct()
    {
        $this->enabled = false;
    }


    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = GlobalHelper::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @return bool|null
     */
    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getLastLoginAt(): ?\DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    /**
     * @param \DateTimeInterface|null $lastLoginAt
     * @return $this
     */
    public function setLastLoginAt(?\DateTimeInterface $lastLoginAt): self
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    /**
     * @return bool
     */
    #[
        SerializedName("isAdmin"),
        Groups(['user:read'])
    ]
    public function isAdmin(): bool
    {
        return in_array(GlobalHelper::ROLE_ADMIN, $this->getRoles());
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param string|null $confirmationToken
     * @return User
     */
    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    /**
     * @param string|null $resetPasswordToken
     * @return $this
     */
    public function setResetPasswordToken(?string $resetPasswordToken): static
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getResetPasswordAt(): ?\DateTimeInterface
    {
        return $this->resetPasswordAt;
    }

    /**
     * @param \DateTimeInterface|null $resetPasswordAt
     * @return $this
     */
    public function setResetPasswordAt(?\DateTimeInterface $resetPasswordAt): static
    {
        $this->resetPasswordAt = $resetPasswordAt;

        return $this;
    }
}
