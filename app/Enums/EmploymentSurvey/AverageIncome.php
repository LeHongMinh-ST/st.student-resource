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
}
