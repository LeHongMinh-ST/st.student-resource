<?php

declare(strict_types=1);

namespace App\DTO\Graduation;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;

class ListGraduationDTO extends BaseListDTO implements BaseDTO
{
    private ?int $year;
    private ?string $certification;
    private ?bool $isGraduationDoesntHaveSurveyPeriod;

    public function __construct()
    {
        parent::__construct();
        $this->isGraduationDoesntHaveSurveyPeriod = null;
    }

    public function getIsGraduationDoesntHaveSurveyPeriod(): ?bool
    {
        return $this->isGraduationDoesntHaveSurveyPeriod;
    }

    public function setIsGraduationDoesntHaveSurveyPeriod(?bool $isGraduationDoesntHaveSurveyPeriod): void
    {
        $this->isGraduationDoesntHaveSurveyPeriod = $isGraduationDoesntHaveSurveyPeriod;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): void
    {
        $this->year = $year;
    }

    public function getCertification(): ?string
    {
        return $this->certification;
    }

    public function setCertification(?string $certification): void
    {
        $this->certification = $certification;
    }

    public function toArray(): array
    {
        return [];
    }
}
