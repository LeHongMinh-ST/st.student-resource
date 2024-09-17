<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusFileImport: string
{
    case Processing = 'processing';
    case Pending = 'pending';
    case Completed = 'completed';
}
