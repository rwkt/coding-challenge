<?php

declare(strict_types=1);

namespace App\Response;

class ApiResponse
{
    /** @var mixed */
    private $data;
    private array $groups;
    private int $status;

    /** @param mixed $data */
    public function __construct($data, array $groups, int $status = 200)
    {
        $this->data = $data;
        $this->groups = $groups;
        $this->status = $status;
    }

    /** @return mixed */
    public function getData()
    {
        return $this->data;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
