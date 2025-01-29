<?php

declare(strict_types=1);

namespace App\Controller\Project;

use App\Entity\BusinessModelCanvas;
use App\Entity\BuyerPersona;
use App\Entity\CompetitorAnalysis;
use App\Entity\GoldenTriangle;
use App\Entity\MarketingMix4;
use App\Entity\MarketingMix5;
use App\Entity\PESTEL;
use App\Entity\Project;
use App\Entity\SMART;
use App\Entity\STP;
use App\Entity\SWOT;
use App\Helper\GlobalHelper;
use App\Helper\ProjectHelper;
use App\Repository\BusinessModelCanvasRepository;
use App\Repository\BuyerPersonaRepository;
use App\Repository\CompetitorAnalysisRepository;
use App\Repository\GoldenTriangleRepository;
use App\Repository\MarketingMix4Repository;
use App\Repository\MarketingMix5Repository;
use App\Repository\PESTELRepository;
use App\Repository\SMARTRepository;
use App\Repository\STPRepository;
use App\Repository\SWOTRepository;
use App\Service\ProjectDocumentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class ProjectDownloadSpecificDocumentController extends AbstractController
{

    /**
     * @param Security $security
     * @param ProjectDocumentService $projectDocumentService
     */
    public function __construct(
        private readonly string                 $appBackUrl,
        private readonly Security               $security,
        private readonly ProjectDocumentService $projectDocumentService,
        private readonly BusinessModelCanvasRepository $businessModelCanvasRepository,
        private readonly BuyerPersonaRepository $buyerPersonaRepository,
        private readonly CompetitorAnalysisRepository $competitorAnalysisRepository,
        private readonly GoldenTriangleRepository $goldenTriangleRepository,
        private readonly MarketingMix4Repository $marketingMix4Repository,
        private readonly MarketingMix5Repository $marketingMix5Repository,
        private readonly PESTELRepository $pestelRepository,
        private readonly SMARTRepository $smartRepository,
        private readonly STPRepository $stpRepository,
        private readonly SWOTRepository $swotRepository,
    ) {}

    /**
     * @param Request $request
     * @param Project $project
     * @return mixed
     * @throws \ReflectionException
     */
    public function __invoke(Request $request, string $id): mixed
    {
        $repository = match ($request->attributes->get('_api_resource_class')) {
            BusinessModelCanvas::class => $this->businessModelCanvasRepository,
            BuyerPersona::class => $this->buyerPersonaRepository,
            CompetitorAnalysis::class => $this->competitorAnalysisRepository,
            GoldenTriangle::class => $this->goldenTriangleRepository,
            MarketingMix4::class => $this->marketingMix4Repository,
            MarketingMix5::class => $this->marketingMix5Repository,
            PESTEL::class => $this->pestelRepository,
            SMART::class => $this->smartRepository,
            STP::class => $this->stpRepository,
            SWOT::class => $this->swotRepository,
            default => throw new NotFoundHttpException('Document not found'),
        };

        $document = $repository->find($id);
        if (!$document) {
            throw new NotFoundHttpException('Document not found');
        }

        ProjectHelper::checkIfUserIsInProjectTeam($this->security->getUser(), $document->getProject());
        ProjectHelper::checkIfTeamIsPremium($document->getProject()->getTeam());

        $mediaObject = $this->projectDocumentService->downloadDocument($document);

        return $this->json(['path' => $this->appBackUrl . '/' . $mediaObject->getContentUrl()], 201);
    }
}
