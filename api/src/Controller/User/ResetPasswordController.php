<?php

declare(strict_types=1);

namespace App\Controller\User;

use ApiPlatform\Validator\ValidatorInterface;
use App\DTO\ResetPasswordDto;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CheckUsersController
 * @package App\Controller
 */
#[AsController]
class ResetPasswordController extends AbstractController
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;

    /**
     * VerifyEmailController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $token
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserRepository $userRepository
     * @return Response
     */
    public function __invoke(string $token, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {
        // Get user
        $user = $userRepository->findOneBy(['resetPasswordToken' => $token]);

        if (!$user) {
            throw new BadRequestHttpException('Invalid token');
        }

        // Check if token expired
        if ($user->getResetPasswordAt()->add(new \DateInterval('PT10M')) < new \DateTime('now')) {
            $user->setResetPasswordToken(null);
            $user->setResetPasswordAt(null);
            $this->entityManager->flush();
            throw new BadRequestHttpException('The token is expired');
        }

        // Get the request content
        $payload = json_decode($request->getContent());
        $newPassword = $payload->newPassword;
        $confirmPassword = $payload->confirmPassword;
        $resetPasswordDto = $serializer->denormalize([
            'newPassword' => $newPassword,
            'confirmPassword' => $confirmPassword
        ], ResetPasswordDto::class);
        $validator->validate($resetPasswordDto);


        // Update user password
        $user->setPassword(
            $passwordHasher->hashPassword($user, $newPassword)
        );
        $user->setResetPasswordToken(null);
        $user->setResetPasswordAt(null);
        $user->setEnabled(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'success' => true
        ]);
    }
}
