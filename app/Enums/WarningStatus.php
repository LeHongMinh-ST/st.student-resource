<?php

declare(strict_types=1);

namespace App\Enums;

enum WarningStatus: string
{
    case UnderObservation = 'under_observation';

    case AtRisk = 'at_risk';

    case NoWarning = 'no_warning';
}
