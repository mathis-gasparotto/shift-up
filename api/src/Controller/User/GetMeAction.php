<?php

declare(strict_types=1);

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 */
class GetMeAction extends AbstractController
{
    /**
     * @return UserInterface
     */
    public function __invoke(): UserInterface
    {
        return $this->getUser();
    }
}
