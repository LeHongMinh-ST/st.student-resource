<?php

declare(strict_types=1);

namespace App\DTO\TrainingIndustry;

use App\Enums\Status;

class CreateTrainingIndustryDTO
{
    private string $name;

    private string $code;
    private string $description;
    private Status $status;

    private int $facultyId;

    public function __construct()
    {
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getFacultyId(): int
    {
        return $this->facultyId;
    }

    public function setFacultyId(int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'code' => $this->getCode(),
            'faculty_id' => $this->getFacultyId(),
            'status' => $this->getStatus(),
            'description' => $this->getDescription(),
        ];
    }
}
