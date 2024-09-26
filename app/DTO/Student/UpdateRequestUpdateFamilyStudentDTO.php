<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\Enums\FamilyRelationship;

class UpdateRequestUpdateFamilyStudentDTO implements BaseDTO
{
    private string|int $id;

    private FamilyRelationship $relationship;

    private string $fullName;

    private string $job;

    private string $phone;

    private int $studentInfoUpdateId;

    public function getId(): int|string
    {
        return $this->id;
    }

    public function setId(int|string $id): void
    {
        $this->id = $id;
    }

    public function getRelationship(): FamilyRelationship
    {
        return $this->relationship;
    }

    public function setRelationship(FamilyRelationship $relationship): void
    {
        $this->relationship = $relationship;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getJob(): string
    {
        return $this->job;
    }

    public function setJob(string $job): void
    {
        $this->job = $job;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getStudentInfoUpdateId(): int
    {
        return $this->studentInfoUpdateId;
    }

    public function setStudentInfoUpdateId(int $studentInfoUpdateId): void
    {
        $this->studentInfoUpdateId = $studentInfoUpdateId;
    }

    public function toArray(): array
    {
        return array_filter([
            'relationship' => $this->relationship->value,
            'full_name' => $this->fullName,
            'job' => $this->job,
            'phone' => $this->phone,
            'student_info_update_id' => $this->studentInfoUpdateId,
        ], fn ($value) => null !== $value);
    }
}
