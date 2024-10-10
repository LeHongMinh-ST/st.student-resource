<?php

declare(strict_types=1);

namespace App\DTO\Graduation;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;

class ListGraduationDTO extends BaseListDTO implements BaseDTO
{
    private ?string $schoolYear;
    private ?string $certification;

    public function getSchoolYear(): ?string
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(?string $schoolYear): void
    {
        $this->schoolYear = $schoolYear;
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
