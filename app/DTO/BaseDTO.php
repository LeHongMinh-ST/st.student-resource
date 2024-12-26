<?php

declare(strict_types=1);

namespace App\DTO;

interface BaseDTO
{
    public function toArray(): array;
}
