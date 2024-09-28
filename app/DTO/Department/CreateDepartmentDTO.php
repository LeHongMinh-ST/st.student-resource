<?php

declare(strict_types=1);

namespace App\DTO\Department;

use App\Enums\Status;

class CreateDepartmentDTO
{
    private string $name;

    private string $code;

    private Status $status;

    private int $facultyId;

    public function __construct()
    {
        $this->status = Status::Enable;
        $this->facultyId = auth()->user()->faculty_id;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function getFacultyId(): int
    {
        return $this->facultyId;
    }

    public function setFacultyId(int $facultyId): void
    {
        $this->facultyId = $facultyId;
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

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'faculty_id' => $this->facultyId,
            'status' => $this->status->value,
        ];
    }
}
