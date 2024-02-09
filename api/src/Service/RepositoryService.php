<?php

namespace App\Service;

use ApiPlatform\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Metadata\Operation;
use App\DTO\ParamDqlDto;
use App\Helper\RepositoryHelper;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 *
 */
class RepositoryService
{
    /**
     * @param ManagerRegistry $managerRegistry
     * @param $collectionExtensions
     */
    public function __construct(private ManagerRegistry $managerRegistry, private $collectionExtensions)
    {
    }

    /**
     * @param string $resourceClass
     * @param ParamDqlDto $paramDqlDto
     * @param Operation $operationName
     * @param array $context
     * @return iterable
     * @throws Exception
     */
    public function createQueryBuilder(string $resourceClass, ParamDqlDto $paramDqlDto, Operation $operationName, array $context): mixed
    {
        $queryBuilder = $this->managerRegistry
            ->getManagerForClass($resourceClass)
            ->getRepository($resourceClass)
            ->createQueryBuilder($paramDqlDto->getAlias());

        $numberOfCondition = count($paramDqlDto->getConditions());

        if ($numberOfCondition) {
            $queryBuilder = match ($numberOfCondition) {
                1 => $this->createQueryBuilderWithOneCondition($paramDqlDto, $queryBuilder),
                default => $this->createQueryBuilderWithLotOfCondition($paramDqlDto, $queryBuilder)
            };
        }

        $queryNameGenerator = new QueryNameGenerator();
        foreach ($this->collectionExtensions as $extension) {
            $extension->applyToCollection($queryBuilder, $queryNameGenerator, $resourceClass, $operationName, $context);

            if ($extension instanceof QueryResultCollectionExtensionInterface && $extension->supportsResult($resourceClass, $operationName, $context)) {
                return $extension->getResult($queryBuilder, $resourceClass, $operationName, $context);
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param ParamDqlDto $paramDqlDto
     * @param QueryBuilder $queryBuilder
     * @return QueryBuilder
     */
    private function createQueryBuilderWithOneCondition(ParamDqlDto $paramDqlDto, QueryBuilder $queryBuilder): QueryBuilder
    {
        $conditions = $paramDqlDto->getConditions();

        foreach ($conditions as $condition) {
            switch ($condition['type']) {
                case RepositoryHelper::TYPES_CONDITION[RepositoryHelper::TYPE_WHERE]:
                    $queryBuilder = $queryBuilder->andWhere($condition['condition']);
                    if (!empty($condition['parameters'])) {
                        foreach ($condition['parameters'] as $index => $parameter) {
                            $queryBuilder = $queryBuilder->setParameter($index, $parameter);
                        }
                    }
                    break;
                case RepositoryHelper::TYPES_CONDITION[RepositoryHelper::TYPE_OR]:
                    $queryBuilder = $queryBuilder->orWhere($condition['condition']);
                    if (!empty($condition['parameters'])) {
                        foreach ($condition['parameters'] as $index => $parameter) {
                            $queryBuilder = $queryBuilder->setParameter($index, $parameter);
                        }
                    }
                    break;
                default:
                    throw new \LogicException('This type of condition does not exist: "' . $condition['type'] . '"');
            }
        }

        return $queryBuilder;
    }

    /**
     * @param ParamDqlDto $paramDqlDto
     * @param QueryBuilder $queryBuilder
     * @return QueryBuilder
     * @throws Exception
     */
    private function createQueryBuilderWithLotOfCondition(ParamDqlDto $paramDqlDto, QueryBuilder $queryBuilder)
    {
        $conditions = $paramDqlDto->getConditions();

        foreach ($conditions as $condition) {
            switch ($condition['type']) {
                case RepositoryHelper::TYPES_CONDITION[RepositoryHelper::TYPE_WHERE]:
                    $queryBuilder = $queryBuilder->andWhere($condition['condition']);

                    if (!empty($condition['parameters'])) {
                        foreach ($condition['parameters'] as $index => $parameter) {
                            $queryBuilder = $queryBuilder->setParameter($index, $parameter);
                        }
                    }
                    break;
                case RepositoryHelper::TYPES_CONDITION[RepositoryHelper::TYPE_OR]:
                    $queryBuilder = $queryBuilder->orWhere($condition['condition']);
                    if (!empty($condition['parameters'])) {
                        foreach ($condition['parameters'] as $index => $parameter) {
                            $queryBuilder = $queryBuilder->setParameter($index, $parameter);
                        }
                    }
                    break;
                case RepositoryHelper::TYPES_CONDITION[RepositoryHelper::TYPE_INNER_JOIN]:
                    if (!array_key_exists('alias', $condition['parameters'])) {
                        throw new Exception('A new alias must be declared for a join of type :' . $condition['type']);
                    }

                    $queryBuilder = $queryBuilder->innerJoin($condition['condition'], $condition['parameters']['alias']);
                    break;
                default:
                    throw new \LogicException('This type of condition does not exist: "' . $condition['type'] . '"');
            }
        }

        return $queryBuilder;
    }
}
