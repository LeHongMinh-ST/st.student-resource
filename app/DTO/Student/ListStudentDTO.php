<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;
use App\Enums\StudentStatus;

class ListStudentDTO extends BaseListDTO implements BaseDTO
{
    private ?string $q;

    private ?StudentStatus $status;

    private ?int $admissionYearId;

    private ?int $classId;

    private ?int $graduationId;

    public function __construct()
    {
        parent::__construct();
        $this->q = null;
        $this->status = null;
        $this->classId = null;
        $this->admissionYearId = null;
        $this->graduationId = null;
        $this->orderBy = 'first_name';
    }

    public function getGraduationId(): ?int
    {
        return $this->graduationId;
    }

    public function setGraduationId(?int $graduationId): void
    {
        $this->graduationId = $graduationId;
    }

    public function getClassId(): ?int
    {
        return $this->classId;
    }

    public function setClassId(?int $classId): void
    {
        $this->classId = $classId;
    }

    public function getQ(): ?string
    {
        return $this->q;
    }

    public function setQ(?string $q): void
    {
        $this->q = $q;
    }

    public function getStatus(): ?StudentStatus
    {
        return $this->status;
    }

    public function setStatus(?StudentStatus $status): void
    {
        $this->status = $status;
    }

    public function getAdmissionYearId(): ?int
    {
        return $this->admissionYearId;
    }

    public function setAdmissionYearId(?int $admissionYearId): void
    {
        $this->admissionYearId = $admissionYearId;
    }

    public function toArray(): array
    {
        return [];
    }
}
