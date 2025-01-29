<?php

namespace App\Service;


use App\Entity\BusinessModelCanvas;
use App\Entity\BuyerPersona;
use App\Entity\CompetitorAnalysis;
use App\Entity\GoldenTriangle;
use App\Entity\MarketingMix4;
use App\Entity\MarketingMix5;
use App\Entity\MediaObject;
use App\Entity\PESTEL;
use App\Entity\Project;
use App\Entity\SMART;
use App\Entity\STP;
use App\Entity\SWOT;
use App\Entity\User;
use App\Helper\AIHelper;
use App\Helper\FileHelper;
use App\Helper\GlobalHelper;
use App\Helper\OpenAIHelper;
use App\Helper\ProjectHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 *
 */
class ProjectDocumentService
{
    /**
     * @param MistralAIService $AIService
     * @param EntityManager $entityManager
     */
    public function __construct(
        private string $prefixUrl,
        private MistralAIService $AIService,
        private EntityManagerInterface $entityManager,
        private FileService $fileService,
    ) {}

    /**
     * @param Project $project
     * @return SWOT
     * @throws ORMException
     * @throws AccessDeniedException
     */
    public function generateSWOT(Project $project, ?User $author): SWOT
    {
        ProjectHelper::checkIfTeamIsAllowedToGenerateDocuments($project->getTeam(), ProjectHelper::PROJECT_DOCUMENT_SWOT);

        ["strengths" => $strengths, "weaknesses" => $weaknesses, "opportunities" => $opportunities, "threats" => $threats] = AIHelper::getResultFromSWOTPrompt(
            $this->AIService->prompt(
                AIHelper::promptForSWOT($project->getDescription())
            )
        );

        $swot = new SWOT();
        $swot->setStrengths($strengths);
        $swot->setWeaknesses($weaknesses);
        $swot->setOpportunities($opportunities);
        $swot->setThreats($threats);
        $swot->setProject($project);
        $swot->setUser($author);

        $project->addSWOT($swot);

        $this->entityManager->persist($swot);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->generateDocumentImage($swot);

        return $swot;
    }

    /**
     * @param Project $project
     * @return BusinessModelCanvas
     * @throws ORMException
     * @throws AccessDeniedException
     */
    public function generateBusinessModelCanvas(Project $project, ?User $author): BusinessModelCanvas
    {
        ProjectHelper::checkIfTeamIsAllowedToGenerateDocuments($project->getTeam(), ProjectHelper::PROJECT_DOCUMENT_BUSINESS_MODEL_CANVAS);

        [
            "keyPartners" => $keyPartners,
            "keyActivities" => $keyActivities,
            "keyResources" => $keyResources,
            "valuePropositions" => $valuePropositions,
            "customerRelationships" => $customerRelationships,
            "channels" => $channels,
            "customerSegments" => $customerSegments,
            "costStructure" => $costStructure,
            "revenueStreams" => $revenueStreams
        ] = AIHelper::getResultFromBusinessModelCanvasPrompt(
            $this->AIService->prompt(
                AIHelper::promptForBusinessModelCanvas($project->getDescription())
            )
        );

        $businessModelCanvas = new BusinessModelCanvas();
        $businessModelCanvas->setKeyPartners($keyPartners);
        $businessModelCanvas->setKeyActivities($keyActivities);
        $businessModelCanvas->setKeyResources($keyResources);
        $businessModelCanvas->setValuePropositions($valuePropositions);
        $businessModelCanvas->setCustomerRelationships($customerRelationships);
        $businessModelCanvas->setChannels($channels);
        $businessModelCanvas->setCustomerSegments($customerSegments);
        $businessModelCanvas->setCostStructure($costStructure);
        $businessModelCanvas->setRevenueStreams($revenueStreams);
        $businessModelCanvas->setProject($project);
        $businessModelCanvas->setUser($author);

        $project->addBusinessModelCanvas($businessModelCanvas);

        $this->entityManager->persist($businessModelCanvas);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->generateDocumentImage($businessModelCanvas);

        return $businessModelCanvas;
    }

    /**
     * @param Project $project
     * @return BuyerPersona
     * @throws ORMException
     * @throws AccessDeniedException
     */
    public function generateBuyerPersona(Project $project, ?User $author): BuyerPersona
    {
        ProjectHelper::checkIfTeamIsAllowedToGenerateDocuments($project->getTeam(), ProjectHelper::PROJECT_DOCUMENT_BUYER_PLAN);

        [
            "personalInfo" => $personalInfo,
            "professionalInfo" => $professionalInfo,
            "goalsChallenges" => $goalsChallenges,
            "communicationChannels" => $communicationChannels,
            "valuesFears" => $valuesFears,
            "negativeInfo" => $negativeInfo
        ] = AIHelper::getResultFromBuyerPersonaPrompt(
            $this->AIService->prompt(
                AIHelper::promptForBuyerPersona($project->getDescription())
            )
        );

        $buyerPersona = new BuyerPersona();
        $buyerPersona->setPersonalInfo($personalInfo);
        $buyerPersona->setProfessionalInfo($professionalInfo);
        $buyerPersona->setGoalsChallenges($goalsChallenges);
        $buyerPersona->setCommunicationChannels($communicationChannels);
        $buyerPersona->setValuesFears($valuesFears);
        $buyerPersona->setNegativeInfo($negativeInfo);
        $buyerPersona->setProject($project);
        $buyerPersona->setUser($author);
        $project->addBuyerPersona($buyerPersona);

        $this->entityManager->persist($buyerPersona);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->generateDocumentImage($buyerPersona);

        return $buyerPersona;
    }

    /**
     * @param Project $project
     * @return SMART
     * @throws ORMException
     * @throws AccessDeniedException
     */
    public function generateSMART(Project $project, ?User $author): SMART
    {
        ProjectHelper::checkIfTeamIsAllowedToGenerateDocuments($project->getTeam(), ProjectHelper::PROJECT_DOCUMENT_SMART);

        [
            "specific" => $specific,
            "measurable" => $measurable,
            "achievable" => $achievable,
            "relevant" => $relevant,
            "timed" => $timed
        ] = AIHelper::getResultFromSMARTPrompt(
            $this->AIService->prompt(
                AIHelper::promptForSMART($project->getDescription())
            )
        );

        $smart = new SMART();
        $smart->setKeySpecific($specific);
        $smart->setKeyMeasurable($measurable);
        $smart->setKeyAchievable($achievable);
        $smart->setKeyRelevant($relevant);
        $smart->setKeyTimed($timed);
        $smart->setProject($project);
        $smart->setUser($author);

        $project->addSMART($smart);

        $this->entityManager->persist($smart);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->generateDocumentImage($smart);

        return $smart;
    }

    /**
     * @param Project $project
     * @return PESTEL
     * @throws ORMException
     * @throws AccessDeniedException
     */
    public function generatePESTEL(Project $project, ?User $author): PESTEL
    {
        ProjectHelper::checkIfTeamIsAllowedToGenerateDocuments($project->getTeam(), ProjectHelper::PROJECT_DOCUMENT_PESTEL);

        [
            "political" => $political,
            "economic" => $economic,
            "social" => $social,
            "technological" => $technological,
            "environmental" => $environmental,
            "legal" => $legal
        ] = AIHelper::getResultFromPESTELPrompt(
            $this->AIService->prompt(
                AIHelper::promptForPESTEL($project->getDescription())
            )
        );

        $pestel = new PESTEL();
        $pestel->setPolitical($political);
        $pestel->setEconomic($economic);
        $pestel->setSocial($social);
        $pestel->setTechnological($technological);
        $pestel->setEnvironmental($environmental);
        $pestel->setLegal($legal);
        $pestel->setProject($project);
        $pestel->setUser($author);

        $project->addPESTEL($pestel);

        $this->entityManager->persist($pestel);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->generateDocumentImage($pestel);

        return $pestel;
    }

    /**
     * @param Project $project
     * @return STP
     * @throws ORMException
     * @throws AccessDeniedException
     */
    public function generateSTP(Project $project, ?User $author): STP
    {
        ProjectHelper::checkIfTeamIsAllowedToGenerateDocuments($project->getTeam(), ProjectHelper::PROJECT_DOCUMENT_STP);

        [
            "segmentation" => $segmentation,
            "targeting" => $targeting,
            "positioning" => $positioning
        ] = AIHelper::getResultFromSTPPrompt(
            $this->AIService->prompt(
                AIHelper::promptForSTP($project->getDescription())
            )
        );

        $stp = new STP();
        $stp->setSegmentation($segmentation);
        $stp->setTargeting($targeting);
        $stp->setPositioning($positioning);
        $stp->setProject($project);
        $stp->setUser($author);

        $project->addSTP($stp);

        $this->entityManager->persist($stp);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->generateDocumentImage($stp);

        return $stp;
    }

    /**
     * @param Project $project
     * @return MarketingMix4
     * @throws ORMException
     * @throws AccessDeniedException
     */
    public function generateMarketingMix4(Project $project, ?User $author): MarketingMix4
    {
        ProjectHelper::checkIfTeamIsAllowedToGenerateDocuments($project->getTeam(), ProjectHelper::PROJECT_DOCUMENT_MARKETING_MIX_4P);

        [
            "product" => $product,
            "price" => $price,
            "place" => $place,
            "promotion" => $promotion
        ] = AIHelper::getResultFromMarketingMix4Prompt(
            $this->AIService->prompt(
                AIHelper::promptForMarketingMix4($project->getDescription())
            )
        );

        $marketingMix4 = new MarketingMix4();
        $marketingMix4->setProduct($product);
        $marketingMix4->setPrice($price);
        $marketingMix4->setPlace($place);
        $marketingMix4->setPromotion($promotion);
        $marketingMix4->setProject($project);
        $marketingMix4->setUser($author);

        $project->addMarketingMix4($marketingMix4);

        $this->entityManager->persist($marketingMix4);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->generateDocumentImage($marketingMix4);

        return $marketingMix4;
    }

    /**
     * @param Project $project
     * @return MarketingMix5
     * @throws ORMException
     * @throws AccessDeniedException
     */
    public function generateMarketingMix5(Project $project, ?User $author): MarketingMix5
    {
        ProjectHelper::checkIfTeamIsAllowedToGenerateDocuments($project->getTeam(), ProjectHelper::PROJECT_DOCUMENT_MARKETING_MIX_5P);

        [
            "product" => $product,
            "price" => $price,
            "place" => $place,
            "promotion" => $promotion,
            "people" => $people
        ] = AIHelper::getResultFromMarketingMix5Prompt(
            $this->AIService->prompt(
                AIHelper::promptForMarketingMix5($project->getDescription())
            )
        );

        $marketingMix5 = new MarketingMix5();
        $marketingMix5->setProduct($product);
        $marketingMix5->setPrice($price);
        $marketingMix5->setPlace($place);
        $marketingMix5->setPromotion($promotion);
        $marketingMix5->setPeople($people);
        $marketingMix5->setProject($project);
        $marketingMix5->setUser($author);

        $project->addMarketingMix5($marketingMix5);

        $this->entityManager->persist($marketingMix5);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->generateDocumentImage($marketingMix5);

        return $marketingMix5;
    }

    /**
     * @param Project $project
     * @return CompetitorAnalysis
     * @throws ORMException
     * @throws AccessDeniedException
     */
    public function generateCompetitorAnalysis(Project $project, ?User $author): CompetitorAnalysis
    {
        ProjectHelper::checkIfTeamIsAllowedToGenerateDocuments($project->getTeam(), ProjectHelper::PROJECT_DOCUMENT_COMPETITOR_ANALYSIS);

        [
            'xAxisLabel' => $xAxisLabel,
            'yAxisLabel' => $yAxisLabel,
            'competitors' => $competitors,
            'ourPosition' => $ourPosition
        ] = AIHelper::getResultFromCompetitorAnalysisPrompt(
            $this->AIService->prompt(
                AIHelper::promptForCompetitorAnalysis($project->getDescription())
            )
        );

        $competitorAnalysis = new CompetitorAnalysis();
        $competitorAnalysis->setProject($project);
        $competitorAnalysis->setXAxisLabel($xAxisLabel);
        $competitorAnalysis->setYAxisLabel($yAxisLabel);
        $competitorAnalysis->setCompetitors($competitors);
        $competitorAnalysis->setOurPosition($ourPosition);
        $competitorAnalysis->setUser($author);

        $project->addCompetitorAnalysis($competitorAnalysis);

        $this->entityManager->persist($competitorAnalysis);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->generateDocumentImage($competitorAnalysis);

        return $competitorAnalysis;
    }

    /**
     * @param Project $project
     * @return GoldenTriangle
     * @throws ORMException
     * @throws AccessDeniedException
     */
    public function generateGoldenTriangle(Project $project, ?User $author): GoldenTriangle
    {
        ProjectHelper::checkIfTeamIsAllowedToGenerateDocuments($project->getTeam(), ProjectHelper::PROJECT_DOCUMENT_GOLDEN_TRIANGLE);

        [
            'topLabel' => $topLabel,
            'leftLabel' => $leftLabel,
            'rightLabel' => $rightLabel,
            'brands' => $brands,
            'ourPosition' => $ourPosition
        ] = AIHelper::getResultFromGoldenTrianglePrompt(
            $this->AIService->prompt(
                AIHelper::promptForGoldenTriangle($project->getDescription())
            )
        );

        $goldenTriangle = new GoldenTriangle();
        $goldenTriangle->setProject($project);
        $goldenTriangle->setTopLabel($topLabel);
        $goldenTriangle->setLeftLabel($leftLabel);
        $goldenTriangle->setRightLabel($rightLabel);
        $goldenTriangle->setBrands($brands);
        $goldenTriangle->setOurPosition($ourPosition);
        $goldenTriangle->setUser($author);

        $project->addGoldenTriangle($goldenTriangle);

        $this->entityManager->persist($goldenTriangle);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $this->generateDocumentImage($goldenTriangle);

        return $goldenTriangle;
    }

    /**
     * @param Project $project
     * @param array $documents
     * @return array<BusinessModelCanvas|BuyerPersona|CompetitorAnalysis|GoldenTriangle|MarketingMix4|MarketingMix5|PESTEL|SMART|STP|SWOT>
     * @throws ORMException
     * @throws AccessDeniedException
     */
    public function generateDocuments(Project $project, array $documents, ?User $author): array
    {
        ProjectHelper::checkIfTeamIsAllowedToGenerateDocuments($project->getTeam(), $documents);

        $toReturn = [];
        foreach ($documents as $document) {
            switch ($document) {
                case ProjectHelper::PROJECT_DOCUMENT_BUSINESS_MODEL_CANVAS:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_BUSINESS_MODEL_CANVAS] = $this->generateBusinessModelCanvas($project, $author);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_BUYER_PLAN:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_BUYER_PLAN] = $this->generateBuyerPersona($project, $author);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_COMPETITOR_ANALYSIS:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_COMPETITOR_ANALYSIS] = $this->generateCompetitorAnalysis($project, $author);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_GOLDEN_TRIANGLE:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_GOLDEN_TRIANGLE] = $this->generateGoldenTriangle($project, $author);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_MARKETING_MIX_4P:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_MARKETING_MIX_4P] = $this->generateMarketingMix4($project, $author);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_MARKETING_MIX_5P:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_MARKETING_MIX_5P] = $this->generateMarketingMix5($project, $author);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_PESTEL:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_PESTEL] = $this->generatePESTEL($project, $author);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_SMART:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_SMART] = $this->generateSMART($project, $author);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_STP:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_STP] = $this->generateSTP($project, $author);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_SWOT:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_SWOT] = $this->generateSWOT($project, $author);
                    break;
            }
            sleep(5);
        }

        return $toReturn;
    }


    /**
     * @param Project $project
     * @return MediaObject
     * @throws ORMException
     */
    public function downloadDocument(SWOT|BusinessModelCanvas|BuyerPersona|CompetitorAnalysis|GoldenTriangle|MarketingMix4|MarketingMix5|PESTEL|SMART|STP $document): MediaObject
    {
        $fileDir = 'teams/' . $document->getProject()->getTeam()->getId() . '/documents';
        $fileName = strtolower(GlobalHelper::getClassShortName($document)) . '-' . $document->getId() . '_' . uniqid();
        $filePath = $fileDir . '/' . $fileName . '.' . FileHelper::FILE_PDF_EXTENSION;

        try {
            $pdf = $this->fileService->generatePdfToHtml($document);
        } catch (Exception $e) {
            // dd($e);
            throw new Exception($e->getMessage());
        }

        try {
            $this->fileService->uploadDestination($pdf, FileHelper::FILE_PDF_EXTENSION, $fileDir, $fileName);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        if (!$document->getFile() || $document->getFile()->getFilePath() !== $filePath) {
            if ($document->getFile()) {
                $this->deleteMediaObjectAndPdf($document);
            }

            $mediaObject = (new MediaObject())
                ->setFilePath($filePath)
                ->setContentUrl($this->prefixUrl . $filePath);

            $this->entityManager->persist($mediaObject);

            $document->setFile($mediaObject);

            $this->entityManager->flush();

            return $mediaObject;
        }

        return $document->getFile()->setContentUrl($this->prefixUrl . '/' . $document->getFile()->getFilePath());
    }

    /**
     * @param Project $project
     * @return MediaObject
     * @throws ORMException
     */
    public function generateDocumentImage(SWOT|BusinessModelCanvas|BuyerPersona|CompetitorAnalysis|GoldenTriangle|MarketingMix4|MarketingMix5|PESTEL|SMART|STP $document): string
    {
        $fileDir = 'teams/' . $document->getProject()->getTeam()->getId() . '/documents';
        $fileName = strtolower(GlobalHelper::getClassShortName($document)) . '-' . $document->getId();
        $filePath = $fileDir . '/' . $fileName . '.' . FileHelper::FILE_JPG_EXTENSION;

        $this->fileService->deleteFileIfExist($filePath);

        try {
            $jpg = $this->fileService->generateJpgToHtml($document);
        } catch (Exception $e) {
            // dd($e);
            throw new Exception($e->getMessage());
        }

        try {
            $this->fileService->uploadDestination($jpg, FileHelper::FILE_JPG_EXTENSION, $fileDir, $fileName);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $filePath;
    }

    /**
     * @param SWOT|BusinessModelCanvas|BuyerPersona|CompetitorAnalysis|GoldenTriangle|MarketingMix4|MarketingMix5|PESTEL|SMART|STP $document
     * @return void
     */
    public function deleteMediaObjectAndPdf(SWOT|BusinessModelCanvas|BuyerPersona|CompetitorAnalysis|GoldenTriangle|MarketingMix4|MarketingMix5|PESTEL|SMART|STP $document): void
    {
        $oldFile = $document->getFile();
        $this->fileService->deleteFileIfExist($oldFile->getFilePath());

        $document->setFile(null);
        $this->entityManager->remove($oldFile);
        $this->entityManager->flush();
    }
}
