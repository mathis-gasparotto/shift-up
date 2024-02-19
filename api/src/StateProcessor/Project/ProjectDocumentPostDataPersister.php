<?php

declare(strict_types=1);

namespace App\StateProcessor\Project;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Helper\GlobalHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class ProjectDocumentPostDataPersister implements ProcessorInterface
{
    /**
     * ProjectPostDataPersister constructor.
     *
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager
    )
    {}

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return mixed
     * @throws Exception
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $user = $this->security->getUser();

        $isAdmin = GlobalHelper::isAdmin($user);

        if (!$isAdmin && $data->getProject()->getTeam()->getManager() !== $user && !$user->isInTeam($data->getProject()->getTeam())) {
            throw new AccessDeniedException();
        }

        $class = $context['operation']->getShortName();
        $addMethod = 'add' . $class;

        $data->getProject()->$addMethod($data);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
