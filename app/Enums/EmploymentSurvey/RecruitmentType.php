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
    // Khác
    case Other = 0;
}
