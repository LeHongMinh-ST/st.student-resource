<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum ProfessionalQualificationField: int
{
    // Chưa phù hợp với trình độ chuyên môn
    case NotMatched = 1;
    // Phù hợp với trình độ chuyên môn
    case Matched = 2;
}
