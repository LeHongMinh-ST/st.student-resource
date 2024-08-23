<?php

declare(strict_types=1);

namespace App\Enums;

enum PostStatus: string
{
    case Publish = 'publish';
    case Draft = 'draft';
    case Hide = 'hide';
}
