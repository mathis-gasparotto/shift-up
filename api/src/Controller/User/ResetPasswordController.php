<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\DTO\ResetPasswordDto;
use App\Repository\UserRepository;
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
class ResetPasswordController extends AbstractController
{
    /**
     * @param string $token
     * @param ResetPasswordDto $data
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserRepository $userRepository
     * @return Response
     */
    public function __invoke(string $token, #[MapRequestPayload] ResetPasswordDto $data, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {
        // Get user
        $user = $userRepository->findOneBy(['resetPasswordToken' => $token]);

        if (!$user) {
            throw new BadRequestHttpException('Invalid token');
        }

        // Check if token expired
        if (!$user->getResetPasswordAt() || $user->getResetPasswordAt()->add(new \DateInterval('PT10M')) < new \DateTime('now')) {
            $user->setResetPasswordToken(null);
            $user->setResetPasswordAt(null);
            $entityManager->flush();
            throw new BadRequestHttpException('The token has expired');
        }

        // Update user password
        $user->setPassword(
            $passwordHasher->hashPassword($user, $data->getPassword())
        );
        $user->setResetPasswordToken(null);
        $user->setResetPasswordAt(null);
        $user->setEnabled(true);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'success' => true
        ]);
    }
}
