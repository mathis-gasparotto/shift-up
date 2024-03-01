<?php

declare(strict_types=1);

namespace App\Controller\Test;

use App\Entity\User;
use App\Repository\ProjectRepository;
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
//#[AsController]
#[Route(
    path: '/test',
    name: 'test',
    methods: ['GET']
)]
class TestController extends AbstractController
{
    public function __construct(private ProjectRepository $projectRepository) {}

    /**
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function __invoke(Request $request): RedirectResponse|JsonResponse
    {
        $project = $this->projectRepository->find('018de487-1e23-776b-9d25-c751c40766d8');
        dd($project->getLastSWOT());
    }
}
