<?php

namespace App\Model;

use DateTimeInterface;

/**
 * Interface TracingAwareInterface
 * @package App\Model
 */
interface TracingAwareInterface
{
    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface;

    /**
     * @param DateTimeInterface $createdAt
     *
     */
    public function setCreatedAt(DateTimeInterface $createdAt);

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface;

    /**
     * @param DateTimeInterface $updatedAt
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt);
}
