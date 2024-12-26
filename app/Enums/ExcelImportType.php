<?php

declare(strict_types=1);

namespace App\Enums;

enum ExcelImportType: string
{
    case Course = 'course';

    case Graduate = 'graduate';

    case Warning = 'warning';

    case Quit = 'quit';
}
