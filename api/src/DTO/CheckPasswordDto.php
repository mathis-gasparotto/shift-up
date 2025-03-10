<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * class CheckPasswordDto
 * package App\DTO
 */
class CheckPasswordDto
{
    /**
     * @var string
     */
    #[
        Assert\NotBlank(message: 'password is required.'),
        Assert\Type('string')
    ]
    private string $password;

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return CheckPasswordDto
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
}