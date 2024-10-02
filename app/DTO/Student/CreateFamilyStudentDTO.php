<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\Enums\FamilyRelationship;

class CreateFamilyStudentDTO implements BaseDTO
{
    private FamilyRelationship $relationship;

    private ?string $fullName;

    private ?string $job;

    private ?string $phone;

    private ?int $studentId;

    public function __construct()
    {
        $this->job = null;
        $this->studentId = null;
        $this->fullName = null;
        $this->phone = null;
    }

    public function getStudentId(): ?int
    {
        return $this->studentId;
    }

    public function setStudentId(?int $studentId): void
    {
        $this->studentId = $studentId;
    }

    public function getRelationship(): FamilyRelationship
    {
        return $this->relationship;
    }

    public function setRelationship(FamilyRelationship $relationship): void
    {
        $this->relationship = $relationship;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): void
    {
        $this->job = $job;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        if (null === $phone) {
            return;
        }
        $checkFormatPhone = preg_match('/^(0|\+84|84)?(3[2-9]|5[2689]|7[06-9]|8[1-689]|9[0-46-9])[0-9]{7}$/', preg_replace('/\s+/', '', $phone));
        if (! $checkFormatPhone) {
            return;
        }
        $this->phone = $phone;
    }

    public function toArray(): array
    {
        return array_filter([
            'relationship' => $this->relationship->value,
            'full_name' => $this->fullName,
            'job' => $this->job,
            'phone' => $this->phone,
            'student_id' => $this->studentId,
        ], fn ($value) => null !== $value);
    }
}
