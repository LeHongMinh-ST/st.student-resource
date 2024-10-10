<?php

declare(strict_types=1);

namespace App\DTO\Quit;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;

class ListStudentQuitDTO extends BaseListDTO implements BaseDTO
{
    private int $semesterId;

    public function getSemesterId(): int
    {
        return $this->semesterId;
    }

    public function setSemesterId(int $semesterId): void
    {
        $this->semesterId = $semesterId;
    }

    public function toArray(): array
    {
        return [];
    }
}
