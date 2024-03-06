<?php

namespace App\Service;


use App\Entity\BusinessModelCanvas;
use App\Entity\BuyerPersona;
use App\Entity\Project;
use App\Entity\SMART;
use App\Entity\SWOT;
use App\Helper\OpenAIHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

/**
 *
 */
class ProjectDocumentService
{
    /**
     * @param OpenAIService $openAIService
     * @param EntityManager $entityManager
     */
    public function __construct(
        private OpenAIService $openAIService,
        private EntityManagerInterface $entityManager
    )
    {}

    /**
     * @param Project $project
     * @return SWOT
     * @throws ORMException
     */
    public function generateSWOT(Project $project): SWOT
    {
        ["Strengths" => $strengths, "Weaknesses" => $weaknesses, "Opportunities" => $opportunities, "Threats" => $threats] = OpenAIHelper::getResultFromSWOTPrompt(
            $this->openAIService->prompt(
                OpenAIHelper::promptForSWOT($project->getDescription())
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
        ] = OpenAIHelper::getResultFromBusinessModelCanvasPrompt(
            $this->openAIService->prompt(
                OpenAIHelper::promptForBusinessModelCanvas($project->getDescription())
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
        ] = OpenAIHelper::getResultFromBuyerPersonaPrompt(
            $this->openAIService->prompt(
                OpenAIHelper::promptForBuyerPersona($project->getDescription())
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
    public function generateSMART(Project $project): SMART
    {
        [
            "Specific" => $specific,
            "Measurable" => $measurable,
            "Achievable" => $achievable,
            "Relevant" => $relevant,
            "Timed" => $timed
        ] = OpenAIHelper::getResultFromSMARTPrompt(
            $this->openAIService->prompt(
                OpenAIHelper::promptForSMART($project->getDescription())
            )
        );

        $smart = new SMART();
        $smart->setKeySpecific($specific);
        $smart->setKeyMeasurable($measurable);
        $smart->setKeyAchievable($achievable);
        $smart->setKeyRelevant($relevant);
        $smart->setKeyTimed($timed);
        $smart->setProject($project);

        $project->addSMART($smart);

        $this->entityManager->persist($smart);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $smart;
    }
}
