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
    private ?string $newPassword;

    /**
     * @var string|null
     */
    #[
        Assert\NotBlank(),
        Assert\EqualTo(null, 'newPassword', message: 'The new password must be confirmed'),
        Assert\Type('string')
    ]
    private ?string $confirmPassword;

    /**
     * @return string|null
     */
    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    /**
     * @param string|null $newPassword
     * @return $this
     */
    public function setNewPassword(?string $newPassword): self
    {
        $this->newPassword = $newPassword;
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
