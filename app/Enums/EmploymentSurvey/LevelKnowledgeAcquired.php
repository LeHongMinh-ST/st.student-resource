<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum LevelKnowledgeAcquired: int
{
    // Đã học được
    case Full = 1;
    // Không học được
    case None = 2;
    // Chỉ học được một phần
    case Partial = 3;

    public function getName(): string
    {
        return match ($this) {
            self::Full => 'Đã học được',
            self::None => 'Không học được',
            self::Partial => 'Chỉ học được một phần',
        };
    }

}
