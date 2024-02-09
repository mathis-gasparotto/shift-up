<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\User;

interface ManagerAwareInterface
{
    /**
     * @return User
     */
    public function getManager(): ?User;

    /**
     * @param User $manager
     *
     * @return self
     */
    public function setManager(User $manager);
}
