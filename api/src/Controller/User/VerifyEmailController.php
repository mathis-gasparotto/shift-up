<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VerifyEmailController
 * @package App\Controller
 */
#[AsController]
class VerifyEmailController extends AbstractController
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
     * @return RedirectResponse|JsonResponse
     */
    #[Route(
        path: '/verify_email_register/{token}',
        name: 'app_verify_email_register',
        requirements: ['token' => '.+'],
        methods: ['GET']
    )]
    public function confirmationRegisterAction(Request $request): RedirectResponse|JsonResponse
    {
        // Get the request content
        $token = $request->get('token');

        if (empty($token)) {
//            return new RedirectResponse($request->server->get('APP_FRONT_URL') . '/?redirect=accountActivationCodeInvalid');
            throw new BadRequestHttpException('Token is empty');
        }

        // Retrieve the user
        /** @var User $user */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['confirmationToken' => $token])
        ;

        // Check we have a user with this confirmation token
        if (!$user) {
//            return new RedirectResponse($request->server->get('APP_FRONT_URL') . '/?redirect=accountActivationCodeInvalid');
            throw new BadRequestHttpException('Invalid confirmation token');
        }

        // Enable the user and remove token
        $user
            ->setEnabled(true)
            ->setConfirmationToken(null)
        ;

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User activated']);
//        return new RedirectResponse($request->server->get('APP_FRONT_URL') . '/?redirect=accountActivated');
    }
}
