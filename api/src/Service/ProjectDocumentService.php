<?php

namespace App\Service;


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
use App\Entity\User;
use App\Helper\AIHelper;
use App\Helper\OpenAIHelper;
use App\Helper\ProjectHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

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
        private MistralAIService $AIService,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @param Project $project
     * @return SWOT
     * @throws ORMException
     */
    public function generateSWOT(Project $project): SWOT
    {
        ["Strengths" => $strengths, "Weaknesses" => $weaknesses, "Opportunities" => $opportunities, "Threats" => $threats] = AIHelper::getResultFromSWOTPrompt(
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

        $project->addSWOT($swot);

        $this->entityManager->persist($swot);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $swot;
    }

    /**
     * @param Project $project
     * @return BusinessModelCanvas
     * @throws ORMException
     */
    public function generateBusinessModelCanvas(Project $project): BusinessModelCanvas
    {
        [
            "Key Partners" => $keyPartners,
            "Key Activities" => $keyActivities,
            "Key Resources" => $keyResources,
            "Value Propositions" => $valuePropositions,
            "Customer Relationships" => $customerRelationships,
            "Channels" => $channels,
            "Customer Segments" => $customerSegments,
            "Cost Structure" => $costStructure,
            "Revenue Streams" => $revenueStreams
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

        $project->addBusinessModelCanvas($businessModelCanvas);

        $this->entityManager->persist($businessModelCanvas);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $businessModelCanvas;
    }

    /**
     * @param Project $project
     * @return BuyerPersona
     * @throws ORMException
     */
    public function generateBuyerPersona(Project $project): BuyerPersona
    {
        [
            "Personal Info" => $personalInfo,
            "Professional Info" => $professionalInfo,
            "Goals Challenges" => $goalsChallenges,
            "Communication Channels" => $communicationChannels,
            "Values Fears" => $valuesFears,
            "Negative Info" => $negativeInfo
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

        $project->addBuyerPersona($buyerPersona);

        $this->entityManager->persist($buyerPersona);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $buyerPersona;
    }

    /**
     * @param Project $project
     * @return SMART
     * @throws ORMException
     */
    public function generateSMART(Project $project, User $author): SMART
    {
        [
            "Specific" => $specific,
            "Measurable" => $measurable,
            "Achievable" => $achievable,
            "Relevant" => $relevant,
            "Timed" => $timed
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

        return $smart;
    }

    /**
     * @param Project $project
     * @return PESTEL
     * @throws ORMException
     */
    public function generatePESTEL(Project $project): PESTEL
    {
        [
            "Political" => $political,
            "Economic" => $economic,
            "Social" => $social,
            "Technological" => $technological,
            "Environmental" => $environmental,
            "Legal" => $legal
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

        $project->addPESTEL($pestel);

        $this->entityManager->persist($pestel);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $pestel;
    }

    /**
     * @param Project $project
     * @return STP
     * @throws ORMException
     */
    public function generateSTP(Project $project): STP
    {
        [
            "Segmentation" => $segmentation,
            "Targeting" => $targeting,
            "Positioning" => $positioning
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

        $project->addSTP($stp);

        $this->entityManager->persist($stp);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $stp;
    }

    /**
     * @param Project $project
     * @return MarketingMix4
     * @throws ORMException
     */
    public function generateMarketingMix4(Project $project): MarketingMix4
    {
        [
            "Product" => $product,
            "Price" => $price,
            "Place" => $place,
            "Promotion" => $promotion
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

        $project->addMarketingMix4($marketingMix4);

        $this->entityManager->persist($marketingMix4);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $marketingMix4;
    }

    /**
     * @param Project $project
     * @return MarketingMix5
     * @throws ORMException
     */
    public function generateMarketingMix5(Project $project): MarketingMix5
    {
        [
            "Product" => $product,
            "Price" => $price,
            "Place" => $place,
            "Promotion" => $promotion,
            "People" => $people
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

        $project->addMarketingMix5($marketingMix5);

        $this->entityManager->persist($marketingMix5);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $marketingMix5;
    }

    /**
     * @param Project $project
     * @param array $documents
     * @return array<BusinessModelCanvas|BuyerPersona|CompetitorAnalysis|GoldenTriangle|MarketingMix4|MarketingMix5|PESTEL|SMART|STP|SWOT>
     * @throws ORMException
     */
    public function generateDocuments(Project $project, array $documents, User $author): array
    {
        $toReturn = [];
        foreach ($documents as $document) {
            switch ($document) {
                case ProjectHelper::PROJECT_DOCUMENT_BUSINESS_MODEL_CANVAS:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_BUSINESS_MODEL_CANVAS] = $this->generateBusinessModelCanvas($project);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_BUYER_PLAN:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_BUYER_PLAN] = $this->generateBuyerPersona($project);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_COMPETITOR_ANALYSIS:
                    //                    TODO: $toReturn[ProjectHelper::PROJECT_DOCUMENT_COMPETITOR_ANALYSIS] = $this->generateCompetitorAnalysis($project);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_GOLDEN_TRIANGLE:
                    //                    TODO: $toReturn[ProjectHelper::PROJECT_DOCUMENT_GOLDEN_TRIANGLE] = $this->generateGoldenTriangle($project);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_MARKETING_MIX_4P:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_MARKETING_MIX_4P] = $this->generateMarketingMix4($project);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_MARKETING_MIX_5P:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_MARKETING_MIX_5P] = $this->generateMarketingMix5($project);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_PESTEL:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_PESTEL] = $this->generatePESTEL($project);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_SMART:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_SMART] = $this->generateSMART($project, $author);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_STP:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_STP] = $this->generateSTP($project);
                    break;
                case ProjectHelper::PROJECT_DOCUMENT_SWOT:
                    $toReturn[ProjectHelper::PROJECT_DOCUMENT_SWOT] = $this->generateSWOT($project);
                    break;
            }
            sleep(5);
        }

        return $toReturn;
    }
}