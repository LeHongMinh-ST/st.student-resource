<?php

declare(strict_types=1);

namespace App\DTO\AdmissionYear;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;

class ListStudentImportDTO extends BaseListDTO implements BaseDTO
{
    protected ?int $admissionYearId;

    protected ?int $graduationCeremoniesId;

    public function __construct()
    {
        $this->admissionYearId = null;
        $this->graduationCeremoniesId = null;
        parent::__construct();
    }

    public function getGraduationCeremoniesId(): ?int
    {
        return $this->graduationCeremoniesId;
    }

    public function setGraduationCeremoniesId(?int $graduationCeremoniesId): void
    {
        $this->graduationCeremoniesId = $graduationCeremoniesId;
    }

    public function getAdmissionYearId(): ?int
    {
        return $this->admissionYearId;
    }

    public function setAdmissionYearId(?int $admissionYearId): void
    {
        $this->admissionYearId = $admissionYearId;
    }

    public function toArray(): array
    {
        return [];
    }
}
