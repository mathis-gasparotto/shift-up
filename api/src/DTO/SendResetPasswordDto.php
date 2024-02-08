<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
class SendResetPasswordDto
{
    /**
     * @var string|null
     */
    #[
        Assert\NotBlank(),
        Assert\Email,
        Assert\Length(max: 180)
    ]
    private ?string $email;

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return SendResetPasswordDto
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }
}
