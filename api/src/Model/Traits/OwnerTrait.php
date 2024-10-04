<?php

namespace App\Model\Traits;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Trait CurrentUserTrait
 * @package App\Model
 */
trait OwnerTrait
{
    /**
     * @var User | null
     */
    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $user;

    /**
     * @return User | null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User | null $user
     * @return $this
     */
    public function setUser(?User $user)
    {
        $this->user = $user;
        return $this;
    }
}