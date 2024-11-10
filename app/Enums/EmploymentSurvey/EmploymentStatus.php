<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum EmploymentStatus: int
{
    // Đã có việc làm
    case Employed = 1;
    // Chưa có việc làm
    case Unemployed = 2;
    // Đang tiếp tục học
    case ContinuingEducation = 3;
    // Chưa đi tìm việc
    case NotLookingForJob = 4;
}
