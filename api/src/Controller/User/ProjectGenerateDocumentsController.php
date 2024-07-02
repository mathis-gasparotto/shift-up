<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\DTO\ProjectGenerateDocumentsDto;
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
use App\Helper\ProjectHelper;
use App\Service\ProjectDocumentService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class ProjectGenerateDocumentsController
 * @package App\Controller
 */
#[AsController]
class ProjectGenerateDocumentsController extends AbstractController
{
    /**
     * @param Project $project
     * @param ProjectGenerateDocumentsDto $data
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     * @param ProjectDocumentService $projectDocumentService
     * @return BusinessModelCanvas[]|BuyerPersona[]|CompetitorAnalysis[]|GoldenTriangle[]|MarketingMix4[]|MarketingMix5[]|PESTEL[]|SMART[]|STP[]|SWOT[]
     * @throws ORMException
     */
    public function __invoke(Project $project, #[MapRequestPayload] ProjectGenerateDocumentsDto $data, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, ProjectDocumentService $projectDocumentService): array
    {
        ProjectHelper::checkIfUserIsInProjectTeam($this->getUser(), $project);

        return $projectDocumentService->generateDocuments($project, $data->getDocuments());
    }
}
