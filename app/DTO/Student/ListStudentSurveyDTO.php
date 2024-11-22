<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;
use App\Enums\StudentSurveyStatus;

class ListStudentSurveyDTO extends BaseListDTO implements BaseDTO
{
    private ?string $q;

    private ?StudentSurveyStatus $status;

    private ?int $classId;

    private ?int $surveyPeriodId;

    private ?int $isResponse;

    public function __construct()
    {
        parent::__construct();
        $this->q = null;
        $this->status = null;
        $this->classId = null;
        $this->graduationId = null;
        $this->surveyPeriodId = null;
        $this->isResponse = null;
        $this->orderBy = 'first_name';
    }

    public function getIsResponse(): ?int
    {
        return $this->isResponse;
    }

    public function setIsResponse(?int $isResponse): void
    {
        $this->isResponse = $isResponse;
    }

    public function getSurveyPeriodId(): ?int
    {
        return $this->surveyPeriodId;
    }

    public function setSurveyPeriodId(?int $surveyPeriodId): void
    {
        $this->surveyPeriodId = $surveyPeriodId;
    }

    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
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

    public function getStatus(): ?StudentSurveyStatus
    {
        return $this->status;
    }

    public function setStatus(?StudentSurveyStatus $status): void
    {
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [];
    }
}
