<?php

declare(strict_types=1);

namespace App\DTO\Graduation;

class CreateGraduationDTO
{
    private string $name;
    private int $year;
    private string $certification;
    private string $certificationDate;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getCertification(): string
    {
        return $this->certification;
    }

    public function setCertification(string $certification): void
    {
        $this->certification = $certification;
    }

    public function getCertificationDate(): string
    {
        return $this->certificationDate;
    }

    public function setCertificationDate(string $certificationDate): void
    {
        $this->certificationDate = $certificationDate;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'year' => $this->year,
            'certification' => $this->certification,
            'certification_date' => $this->certificationDate,
        ];
    }
}
