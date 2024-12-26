<?php

declare(strict_types=1);

namespace App\Enums;

enum StudentRole: string
{
    case President = 'president';

    case VicePresident = 'vice_president';

    case Secretary = 'secretary';
    case ViceSecretary = 'vice_secretary';
    case Basic = 'basic';
}
