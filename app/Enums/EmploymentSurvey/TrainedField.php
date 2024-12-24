<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum TrainedField: int
{
    case RightTraining = 1;
    case RelatedTraining = 2;
    case NotRelatedTraining = 3;

    public function getName(): string
    {
        return match ($this) {
            self::RightTraining => 'Đúng ngành đào tạo',
            self::RelatedTraining => 'Liên quan đến ngành đào tạo',
            self::NotRelatedTraining => 'Không liên quan đến ngành đào tạo',
        };
    }
}
