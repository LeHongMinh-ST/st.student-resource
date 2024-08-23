<?php

declare(strict_types=1);

namespace App\Enums;

enum AuthApiSection: string
{
    case Admin = 'api';

    case System = 'system';

    case Student = 'student';
}
