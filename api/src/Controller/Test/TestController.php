<?php

declare(strict_types=1);

namespace App\Controller\Test;

use App\Service\OpenAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TestController
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
    public function __construct(private OpenAIService $openAIService) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {

        $result = $this->openAIService->prompt('What is the meaning of life?');

        return $this->json($result); // an open-source, widely-used, server-side scripting language.
    }
}
