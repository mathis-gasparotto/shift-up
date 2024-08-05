<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
class ResetPasswordDto
{
    /**
     * @var string|null
     */
    #[
        Assert\NotBlank(),
        Assert\Length(min: 8, max: 150),
        Assert\Regex(pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>])(?!.*\s).*$/', message: 'Password must contain at least one lowercase letter, one uppercase letter, one number and one special character'),
        Assert\Type('string')
    ]
    private ?string $password;

    /**
     * @var string|null
     */
    #[
        Assert\NotBlank(),
        Assert\EqualTo(propertyPath: 'password', message: 'The new password must be confirmed'),
        Assert\Type('string')
    ]
    private ?string $confirmPassword;

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return $this
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    /**
     * @param string|null $confirmPassword
     * @return $this
     */
    public function setConfirmPassword(?string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;
        return $this;
    }
}
