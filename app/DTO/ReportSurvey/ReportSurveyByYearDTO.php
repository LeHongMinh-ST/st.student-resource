<?php

declare(strict_types=1);

namespace App\DTO\ReportSurvey;

class ReportSurveyByYearDTO
{
    private int $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

}
