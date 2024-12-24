<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum EmployedSince: int
{
    // Dưới 3 tháng
    case LessThan3Months = 1;
    // Từ 3 đến 6 tháng
    case From3To6Months = 2;
    // Từ 6 đến 12 tháng
    case From6To12Months = 3;
    // Trên 12 tháng
    case MoreThan12Months = 4;

    public function getName(): string
    {
        return match ($this) {
            self::LessThan3Months => 'Dưới 3 tháng',
            self::From3To6Months => 'Từ 3 đến 6 tháng',
            self::From6To12Months => 'Từ 6 đến 12 tháng',
            self::MoreThan12Months => 'Trên 12 tháng',
        };
    }
}
