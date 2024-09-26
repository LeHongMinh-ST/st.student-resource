<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;
use App\Enums\StudentInfoUpdateStatus;

class ListRequestUpdateStudentDTO extends BaseListDTO implements BaseDTO
{
    private ?StudentInfoUpdateStatus $status;
    private ?int $studentId;

    private ?int $classId;

    public function __construct()
    {
        parent::__construct();
        $this->status = null;
    }

    public function getStatus(): ?StudentInfoUpdateStatus
    {
        return $this->status;
    }

    public function setStatus(?StudentInfoUpdateStatus $status): void
    {
        $this->status = $status;
    }

    public function getStudentId(): ?int
    {
        return $this->studentId;
    }

    public function setStudentId(?int $studentId): void
    {
        $this->studentId = $studentId;
    }

    public function getClassId(): ?int
    {
        return $this->classId;
    }

    public function setClassId(?int $classId): void
    {
        $this->classId = $classId;
    }

    public function toArray(): array
    {
        return [];
    }
}
