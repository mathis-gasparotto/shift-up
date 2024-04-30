<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\DTO\SendResetPasswordDto;
use App\Helper\GlobalHelper;
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
class SendResetPasswordController extends AbstractController
{
    /**
     * @param SendResetPasswordDto $data
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function __invoke(#[MapRequestPayload] SendResetPasswordDto $data, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        // Check if email is good
        $user = $userRepository->findOneBy(['email' => $data->getEmail()]);

        if (!$user) {
            throw new BadRequestHttpException('Invalid email');
        }

        // Generate token
        $token =  GlobalHelper::createToken($user->getEmail(), $user->getLastName());

        // Add token to the user
        $user->setResetPasswordToken($token);
        $now = new \DateTime();
        $user->setResetPasswordAt($now);

        $entityManager->persist($user);
        $entityManager->flush();

        // TODO: Send email to user

        return $this->json([
            'success' => true
        ]);
    }
}
