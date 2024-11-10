<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum WorkArea: int
{
    case StateSector = 1;
    case PrivateSector = 2;
    case ElementForeignSector = 3;
    case SelfEmployment = 4;
}
