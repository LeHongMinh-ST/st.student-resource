<?php

declare(strict_types=1);

namespace App\DTO\Graduation;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;

class ListGraduationDTO extends BaseListDTO implements BaseDTO
{
    private ?int $schoolYearId;
    private ?string $certification;

    public function getSchoolYearId(): ?int
    {
        return $this->schoolYearId;
    }

    public function setSchoolYear(?int $schoolYearId): void
    {
        $this->schoolYearId = $schoolYearId;
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
