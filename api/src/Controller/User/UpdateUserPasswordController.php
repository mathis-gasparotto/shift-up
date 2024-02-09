<?php

declare(strict_types=1);

namespace App\Controller\User;

use ApiPlatform\Validator\ValidatorInterface;
use App\DTO\UpdatePasswordDto;
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
class UpdateUserPasswordController extends AbstractController
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
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    public function __invoke(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Get the request content
        $payload = json_decode($request->getContent());
        $currentPassword = $payload->currentPassword;
        $newPassword = $payload->newPassword;
        $confirmPassword = $payload->confirmPassword;
        $updateUserPasswordDto = $serializer->denormalize([
            'currentPassword' => $currentPassword,
            'newPassword' => $newPassword,
            'confirmPassword' => $confirmPassword
        ], UpdatePasswordDto::class);
        $validator->validate($updateUserPasswordDto);

        // Check if old password is good
        $user = $this->getUser();
        if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
            throw new BadRequestHttpException('Incorrect current password');
        }

        // Update user password
        $user->setPassword(
            $passwordHasher->hashPassword($user, $newPassword)
        );
//        $user->eraseCredentials();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'success' => true
        ]);
    }
}
