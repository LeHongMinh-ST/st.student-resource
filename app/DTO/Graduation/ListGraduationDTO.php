<?php

declare(strict_types=1);

namespace App\DTO\Graduation;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;

class ListGraduationDTO extends BaseListDTO implements BaseDTO
{
    public function toArray(): array
    {
        return [];
    }
}
