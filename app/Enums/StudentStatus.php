<?php

declare(strict_types=1);

namespace App\Enums;

enum StudentStatus: string
{
    case CurrentlyStudying = 'currently_studying';
    case Graduated = 'graduated';

    case ToDropOut = 'to_drop_out';

    case TemporarilySuspended = 'temporarily_suspended';
    case Expelled = 'expelled';
    case Deferred = 'deferred';
    case TransferStudy = 'transfer_study';
}
