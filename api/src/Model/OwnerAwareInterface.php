<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\User;

interface OwnerAwareInterface
{
    /**
     * @return User
     */
    public function getUser(): ?User;

    /**
     * @param User $site
     *
     * @return self
     */
    public function setUser(User $site);
}