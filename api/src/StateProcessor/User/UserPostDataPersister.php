<?php

declare(strict_types=1);

namespace App\StateProcessor\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Helper\EmailHelper;
use App\Helper\GlobalHelper;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 *
 */
class UserPostDataPersister implements ProcessorInterface
{
    /**
     * UserDataPersister constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $userPasswordEncoder
     */
    public function __construct(
        private string $appFrontUrl,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordEncoder,
        private EmailService $emailService
    ) {
    }

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return mixed
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $plainPassword = $data->getPassword();

        $data->setPassword(
            $this->userPasswordEncoder->hashPassword($data, $plainPassword)
        );
        $data->eraseCredentials();
        $data->setRoles([GlobalHelper::ROLE_USER]);

        // Generate token
        $token =  GlobalHelper::createToken($data->getEmail(), $data->getLastName());

        // Add token to the user
        $data->setConfirmationToken($token);

        // Send email to the user
        $confirmationLink = $this->appFrontUrl . '/verify-email/' . $token;
        $this->emailService->sendEmailService(
            $data->getEmail(),
            EmailHelper::EMAIL_TYPE_CONFIRM_EMAIL,
            ['confirmation_link' => $confirmationLink]
        );

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
