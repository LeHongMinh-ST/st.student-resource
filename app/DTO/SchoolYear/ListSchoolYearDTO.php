<?php

declare(strict_types=1);

namespace App\DTO\SchoolYear;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;

class ListSchoolYearDTO extends BaseListDTO implements BaseDTO
{
    public function __construct()
    {
        parent::__construct();
        $this->orderBy = 'start_year';
    }

    public function toArray(): array
    {
        return [];
    }
}
