<?php

declare(strict_types=1);

namespace App\StateProcessor\Project;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\SWOT;
use App\Helper\GlobalHelper;
use App\Helper\ProjectHelper;
use App\Service\ProjectDocumentService;
use Exception;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class GenerateProjectDocumentDataPersister implements ProcessorInterface
{
    /**
     * ProjectPostDataPersister constructor.
     *
     * @param Security $security
     * @param ProjectDocumentService $projectDocumentService
     */
    public function __construct(
        private Security $security,
        private ProjectDocumentService $projectDocumentService,
    )
    {}

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return SWOT
     * @throws Exception
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): SWOT
    {
        $project = $data->getProject();

        ProjectHelper::checkIfUserIsInProjectTeam($this->security->getUser(), $project);

        $class = GlobalHelper::getClassShortName($context['operation']->getClass());
        $generateMethod = 'generate' . $class;

        return $this->projectDocumentService->$generateMethod($project);
    }
}
