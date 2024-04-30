<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\DTO\UpdatePasswordDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CheckUsersController
 * @package App\Controller
 */
#[AsController]
class UpdateUserPasswordController extends AbstractController
{
    /**
     * @param UpdatePasswordDto $data
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    public function __invoke(#[MapRequestPayload] UpdatePasswordDto $data, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Check if old password is good
        $user = $this->getUser();
        if (!$passwordHasher->isPasswordValid($user, $data->getCurrentPassword())) {
            throw new BadRequestHttpException('Incorrect current password');
        }

        // Update user password
        $user->setPassword(
            $passwordHasher->hashPassword($user, $data->getNewPassword())
        );
//        $user->eraseCredentials();

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'success' => true
        ]);
    }
}
