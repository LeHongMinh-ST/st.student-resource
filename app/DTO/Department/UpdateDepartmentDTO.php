<?php

declare(strict_types=1);

namespace App\DTO\Department;

use App\Enums\Status;

class UpdateDepartmentDTO
{
    private ?int $id;

    private ?string $name;

    private ?string $code;

    private ?Status $status;

    public function __construct()
    {
        $this->id = null;
        $this->name = null;
        $this->code = null;
        $this->status = null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): void
    {
        $this->status = $status;
    }

    // Method to convert the object to an array
    public function toArray(): array
    {
        $generalClassArray = [
            'name' => $this->name,
            'code' => $this->code,
            'status' => $this->status,
        ];

        return array_filter($generalClassArray, fn ($value) => null !== $value);
    }
}
