<?php

namespace App\Model\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait TracingTrait
 * @package App\Model
 */
trait TracingAwareTrait
{
    /**
     * @var DateTimeInterface
     *
     */
    #[
        ORM\Column(type:"datetime"),
        Gedmo\Timestampable(on:"create")
    ]
    private DateTimeInterface $createdAt;

    /**
     * @var DateTimeInterface
     *
     *
     */
    #[
        ORM\Column(type:"datetime"),
        Gedmo\Timestampable(on:"update"),
        Groups(['project:read'])
    ]
    private DateTimeInterface $updatedAt;

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     *
     * @return TracingAwareTrait
     */
    public function setCreatedAt(DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     * @return TracingAwareTrait
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
