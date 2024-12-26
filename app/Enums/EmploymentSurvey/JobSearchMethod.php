<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum JobSearchMethod: int
{
    // Do Học viện/Khoa giới thiệu
    case Academy = 1;
    // Tự tìm việc làm
    case Self = 2;
    // Bạn bè, người quen giới thiệu
    case Friend_And_Relative = 3;
    // Tự tạo việc làm
    case Create = 4;
    // Hinh thức khác
    case Other = 0;

    public function getName(): string
    {
        return match ($this) {
            self::Academy => 'Do Học viện/Khoa giới thiệu',
            self::Self => 'Tự tìm việc làm',
            self::Friend_And_Relative => 'Bạn bè, người quen giới thiệu',
            self::Create => 'Tự tạo việc làm',
            self::Other => 'Hinh thức khác',
        };
    }
}
