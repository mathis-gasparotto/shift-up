<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\DTO\CheckPasswordDto;
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
class CheckUsersController extends AbstractController
{
    /**
     * @param CheckPasswordDto $data
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    public function __invoke(#[MapRequestPayload] CheckPasswordDto $data, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Check if password is good
        $user = $this->getUser();
        if ($user && $passwordHasher->isPasswordValid($user, $data->getPassword())) {
            return $this->json([
                'message' => 'Correct password'
            ]);
        }
        throw new BadRequestHttpException('Incorrect password');
    }
}