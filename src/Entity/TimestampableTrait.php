<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{
    /** @ORM\Column(type="datetime", nullable=false) */
    private $createdAt;

    /** @ORM\Column(type="datetime", nullable=false) */
    private $updatedAt;

    /** @ORM\PrePersist() */
    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /** @ORM\PreUpdate() */
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTime();
    }
}
