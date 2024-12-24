<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum AverageIncome: int
{
    // Dưới 5 triệu
    case LessThan5Million = 1;
    // Từ 5 đến 10 triệu
    case From5To10Million = 2;
    // Từ 10 đến 15 triệu
    case From10To15Million = 3;
    // Trên 15 triệu
    case MoreThan15Million = 4;

    public function getName(): string
    {
        return match ($this) {
            self::LessThan5Million => 'Dưới 5 triệu',
            self::From5To10Million => 'Từ 5 đến 10 triệu',
            self::From10To15Million => 'Từ 10 đến 15 triệu',
            self::MoreThan15Million => 'Trên 15 triệu',
        };
    }
}
