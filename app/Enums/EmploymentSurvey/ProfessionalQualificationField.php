<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum ProfessionalQualificationField: int
{
    // Chưa phù hợp với trình độ chuyên môn
    case NotMatched = 1;
    // Phù hợp với trình độ chuyên môn
    case Matched = 2;

    public function getName(): string
    {
        return match ($this) {
            self::NotMatched => 'Chưa phù hợp với trình độ chuyên môn',
            self::Matched => 'Phù hợp với trình độ chuyên môn',
        };
    }
}
