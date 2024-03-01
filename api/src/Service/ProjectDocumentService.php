<?php

namespace App\Service;


use App\Entity\Project;
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
}
