<?php

declare(strict_types=1);

namespace App\Controller\User;

use ApiPlatform\Validator\ValidatorInterface;
use App\DTO\SendResetPasswordDto;
use App\Helper\GlobalHelper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CheckUsersController
 * @package App\Controller
 */
#[AsController]
class SendResetPasswordController extends AbstractController
{
    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function __invoke(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        // Get the request content
        $payload = json_decode($request->getContent());
        $email = $payload->email;

        // Check if email is good
        $user = $userRepository->findOneBy(['email' => $email]);

        $checkPasswordDto = $serializer->denormalize([
            'email' => $email
        ], SendResetPasswordDto::class);
        $validator->validate($checkPasswordDto);

        if ($user) {
            // Generate token
            $token =  GlobalHelper::createToken($user->getEmail(), $user->getLastName());

            // Add token to the user
            $user->setResetPasswordToken($token);
            $now = new \DateTime();
            $user->setResetPasswordAt($now);

            $entityManager->persist($user);
            $entityManager->flush();

            // TODO: Send email to user
        }

        return $this->json([
            'success' => true
        ]);
    }
}
