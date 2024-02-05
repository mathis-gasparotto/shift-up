<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ParamDqlDto
{
    /**
     * @var string|null
     */
    #[
        Assert\NotBlank,
        Assert\Type(type: 'string'),
        Assert\Length(max: 3, maxMessage: 'The alias may not exceed {{ limit }} characters')
    ]
    private ?string $alias;

    /**
     * @var array
     */
    private array $conditions = [];

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param string|null $alias
     * @return ParamDqlDto
     */
    public function setAlias(?string $alias): static
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return array
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param array $conditions
     * @return ParamDqlDto
     */
    public function setConditions(array $conditions): static
    {
        $this->conditions = $conditions;
        return $this;
    }
}
