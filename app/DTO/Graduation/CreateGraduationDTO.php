<?php

declare(strict_types=1);

namespace App\DTO\Graduation;

class CreateGraduationDTO
{
    private string $name;
    private int $schoolYearId;
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

    public function getSchoolYearId(): int
    {
        return $this->schoolYearId;
    }

    public function setSchoolYearId(int $schoolYearId): void
    {
        $this->schoolYearId = $schoolYearId;
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
            'school_year_id' => $this->schoolYearId,
            'certification' => $this->certification,
            'certification_date' => $this->certificationDate,
        ];
    }
}
