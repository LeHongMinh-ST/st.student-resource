<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum WorkArea: int
{
    case StateSector = 1;
    case PrivateSector = 2;
    case ElementForeignSector = 3;
    case SelfEmployment = 4;

    public function getName(): string
    {
        return match ($this) {
            self::StateSector => 'Khu vực nhà nước',
            self::PrivateSector => 'Khu vực tư nhân',
            self::ElementForeignSector => 'Có yếu tố nước ngoài',
            self::SelfEmployment => 'Tự tạo việc làm',
        };
    }
}
