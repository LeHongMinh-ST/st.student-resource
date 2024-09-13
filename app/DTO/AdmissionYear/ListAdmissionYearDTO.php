<?php

declare(strict_types=1);

namespace App\DTO\AdmissionYear;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;

class ListAdmissionYearDTO extends BaseListDTO implements BaseDTO
{
    public function __construct()
    {
        parent::__construct();
        $this->orderBy = 'admission_year';
    }

    public function toArray(): array
    {
        return [];
    }
}
