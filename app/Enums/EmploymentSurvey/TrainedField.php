<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum TrainedField: int
{
    case RightTraining = 1;
    case RelatedTraining = 2;
    case NotRelatedTraining = 3;
}
