<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: string
{
    case Enable = 'enable';
    case Disable = 'disable';
    case Draft = 'draft';
}
