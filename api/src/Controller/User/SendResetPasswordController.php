<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\DTO\SendResetPasswordDto;
use App\Helper\EmailHelper;
use App\Helper\GlobalHelper;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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
     * @param string $appFrontUrl
     * @param SendResetPasswordDto $data
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param EmailService $emailService
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function __invoke(string $appFrontUrl, #[MapRequestPayload] SendResetPasswordDto $data, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository, EntityManagerInterface $entityManager, EmailService $emailService): Response
    {
        // Check if email is good
        $user = $userRepository->findOneBy(['email' => $data->getEmail()]);

        if (!$user) {
//            throw new BadRequestHttpException('Invalid email');
            return $this->json([
                'success' => true
            ]);
        }

        // Generate token
        $token =  GlobalHelper::createToken($user->getEmail(), $user->getLastName());

        // Add token to the user
        $user->setResetPasswordToken($token);
        $now = new \DateTime();
        $user->setResetPasswordAt($now);

        $entityManager->persist($user);
        $entityManager->flush();

        $emailService->sendEmailService(
            $user->getEmail(),
            EmailHelper::EMAIL_TYPE_RESET_PASSWORD,
            [
                'user_first_name' => $user->getFirstName(),
                'reset_link' => $appFrontUrl . '/reset-password/' . $user->getResetPasswordToken()
            ]
        );

        return $this->json([
            'success' => true
        ]);
    }
}
