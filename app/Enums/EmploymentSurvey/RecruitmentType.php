<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum RecruitmentType: int
{
    // Thi tuyển
    case Exam = 1;
    // Xét tuyển
    case Recruitment = 2;
    // Hợp đồng
    case Contract = 3;
    // Biệt phái
    case Seconded = 4;
    // Điều động
    case Mobilized = 5;
    // Khác
    case Other = 0;

    public function getName(): string
    {
        return match ($this) {
            self::Exam => 'Thi tuyển',
            self::Recruitment => 'Xét tuyển',
            self::Contract => 'Hợp đồng',
            self::Seconded => 'Biệt phái',
            self::Mobilized => 'Điều động',
            self::Other => 'Khác',
        };
    }
}
