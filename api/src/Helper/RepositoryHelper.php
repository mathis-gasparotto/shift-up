<?php

declare(strict_types=1);

namespace App\Helper;

use App\DTO\ParamDqlDto;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

final class RepositoryHelper
{
    /** @var string  */
    public const TYPE_WHERE = 'where';

    /** @var string  */
    public const TYPE_AND = 'AND';

    /** @var string  */
    public const TYPE_OR = 'OR';

    /** @var string  */
    public const TYPE_CROSS_JOIN = 'cross_join';

    /** @var string  */
    public const TYPE_INNER_JOIN = 'inner_join';

    /** @var string  */
    public const TYPE_LEFT_JOIN = 'left_join';

    /** @var string  */
    public const TYPE_RIGHT_JOIN = 'right_join';

    /** @var string  */
    public const TYPE_FULL_JOIN = 'full_join';

    /** @var string  */
    public const TYPE_SELF_JOIN = 'self_join';

    /** @var string  */
    public const TYPE_NATURAL_JOIN = 'natural_join';

    /** @var string[]  */
    public const TYPES_CONDITION = [
        self::TYPE_WHERE => self::TYPE_WHERE,
        self::TYPE_OR => self::TYPE_OR,
        self::TYPE_CROSS_JOIN => self::TYPE_CROSS_JOIN,
        self::TYPE_INNER_JOIN => self::TYPE_INNER_JOIN,
        self::TYPE_LEFT_JOIN => self::TYPE_LEFT_JOIN,
        self::TYPE_RIGHT_JOIN => self::TYPE_RIGHT_JOIN,
        self::TYPE_FULL_JOIN => self::TYPE_FULL_JOIN,
        self::TYPE_SELF_JOIN => self::TYPE_SELF_JOIN,
        self::TYPE_NATURAL_JOIN => self::TYPE_NATURAL_JOIN,
        self::TYPE_AND => self::TYPE_AND,
    ];

    /**
     * @param string $alias
     * @param array $conditions
     * @return ParamDqlDto
     * @throws Exception
     */
    #[ArrayShape(['alias' => "string", 'conditions' => "string[]"])] public static function createParamDql(
        string $alias,
        array $conditions = []
    ): ParamDqlDto {
        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                if (
                    !array_key_exists('type', $condition) ||
                    !array_key_exists('condition', $condition) ||
                    !array_key_exists('parameters', $condition)
                ) {
                    throw new \Exception('the condition array must have the following keys: type ,condition and parameters');
                }

                if (!is_string($condition['type']) || !is_string($condition['condition'])) {
                    throw new Exception('the value of the "type" or "condition" key must be a string');
                } elseif (!is_array($condition['parameters'])) {
                    throw new Exception('the value of the "type" or "condition" key must be a array');
                }

                $condition['type'] = strtolower($condition['type']);

                if (!array_key_exists($condition['type'], self::TYPES_CONDITION)) {
                    throw new Exception('the value "' . $condition['type'] . '" of the "type" is not correct, the types of requete allowed are : ' . implode(', ', self::TYPES_CONDITION));
                }
            }
        }

        return (new ParamDqlDto())
            ->setAlias($alias)
            ->setConditions($conditions);
    }
}
