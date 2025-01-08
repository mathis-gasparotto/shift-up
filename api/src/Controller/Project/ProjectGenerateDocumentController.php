<?php

declare(strict_types=1);

namespace App\Controller\Project;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Entity\Project;
use App\Helper\GlobalHelper;
use App\Helper\ProjectHelper;
use App\Service\ProjectDocumentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;

/**
 *
 */
class ProjectGenerateDocumentController extends AbstractController
{

    /**
     * @param Security $security
     * @param ProjectDocumentService $projectDocumentService
     */
    public function __construct(
        private readonly Security               $security,
        private readonly ProjectDocumentService $projectDocumentService,
    ) {}

    /**
     * @param Request $request
     * @param Project $project
     * @return mixed
     * @throws \ReflectionException
     */
    public function __invoke(Request $request, Project $project): mixed
    {
        ProjectHelper::checkIfUserIsInProjectTeam($this->security->getUser(), $project);

        $class = GlobalHelper::getClassShortName($request->attributes->get('_api_resource_class'));
        $generateMethod = 'generate' . $class;

        $document = $this->projectDocumentService->$generateMethod($project);

        return $this->json($document, 201);
    }
}
