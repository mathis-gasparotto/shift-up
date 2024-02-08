<?php

declare(strict_types=1);

namespace App\StateProcessor\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Helper\GlobalHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $userPasswordEncoder)
    {}

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return mixed
     * @throws Exception
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

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
