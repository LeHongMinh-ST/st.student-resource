<?php

declare(strict_types=1);

namespace App\Enums;

enum StudentSurveyStatus: string
{
    case surveyed = 'surveyed';
    case notSurveyed = 'not_surveyed';
}
