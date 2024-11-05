<?php

declare(strict_types=1);

namespace App\DTO\SurveyPeriod;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;
use App\Enums\Status;
use App\Enums\SurveyPeriodType;

class ListSurveyPeriodDTO extends BaseListDTO implements BaseDTO
{
    protected ?string $q;

    protected ?int $facultyId;

    protected ?Status $status;

    protected ?SurveyPeriodType $type;

    protected ?string $year;

    protected ?string $startDate;
    protected ?string $endDate;

    public function __construct()
    {
        parent::__construct();
        $this->q = null;
        $this->facultyId = auth()->user()->faculty_id;
        $this->status = null;
        $this->type = null;
        $this->startDate = null;
        $this->endDate = null;
        $this->year = null;
        $this->orderBy = 'id';
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function setStartDate(?string $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function setEndDate(?string $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): void
    {
        $this->year = $year;
    }

    public function getFacultyId(): ?int
    {
        return $this->facultyId;
    }

    public function setFacultyId(?int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): void
    {
        $this->status = $status;
    }

    public function getType(): ?SurveyPeriodType
    {
        return $this->type;
    }

    public function setType(?SurveyPeriodType $type): void
    {
        $this->type = $type;
    }

    public function getQ(): ?string
    {
        return $this->q;
    }

    public function setQ(?string $q): void
    {
        $this->q = $q;
    }

    public function toArray(): array
    {
        return [];
    }
}
