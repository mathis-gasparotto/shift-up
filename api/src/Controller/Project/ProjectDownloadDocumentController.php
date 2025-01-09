<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\Project;
use App\Helper\GlobalHelper;
use App\Helper\ProjectHelper;
use App\Service\ProjectDocumentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\SecurityBundle\Security;

/**
 *
 */
class ProjectDownloadDocumentController extends AbstractController
{

    /**
     * @param Security $security
     * @param ProjectDocumentService $projectDocumentService
     */
    public function __construct(
        private readonly string                 $appBackUrl,
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
        ProjectHelper::checkIfTeamIsPremium($project->getTeam());

        $class = GlobalHelper::getClassShortName($request->attributes->get('_api_resource_class'));
        $getMethod = 'getLast' . $class;
        $document = $project->$getMethod();

        if (!$document) {
            throw new NotFoundHttpException('Document not found');
        }

        $mediaObject = $this->projectDocumentService->downloadDocument($document);

        return $this->json(['path' => $this->appBackUrl . $mediaObject->getContentUrl()], 201);
    }
}