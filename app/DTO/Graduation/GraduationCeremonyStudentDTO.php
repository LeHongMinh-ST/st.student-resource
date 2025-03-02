<?php

declare(strict_types=1);

namespace App\DTO\Graduation;

class GraduationCeremonyStudentDTO
{
    private int|string $id;
    private string $name;
    private string $year;
    private string $certification;
    private string $certificationDate;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(int|string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getYear(): string
    {
        return $this->year;
    }

    public function setYear(string $year): void
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
            'school_year' => $this->year,
            'certification' => $this->certification,
            'certification_date' => $this->certificationDate,
        ];
    }
}
