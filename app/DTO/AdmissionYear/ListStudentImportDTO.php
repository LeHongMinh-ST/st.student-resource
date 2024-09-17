<?php

declare(strict_types=1);

namespace App\DTO\AdmissionYear;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;

class ListStudentImportDTO extends BaseListDTO implements BaseDTO
{
    protected int $admissionYearId;

    public function __construct()
    {
        parent::__construct();
    }

    public function getAdmissionYearId(): int
    {
        return $this->admissionYearId;
    }

    public function setAdmissionYearId(int $admissionYearId): void
    {
        $this->admissionYearId = $admissionYearId;
    }


    public function toArray(): array
    {
        return [];
    }
}
