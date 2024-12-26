<?php

declare(strict_types=1);

namespace App\DTO\Warning;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;

class ListStudentWarningDTO extends BaseListDTO implements BaseDTO
{
    private ?int $semesterId;

    public function __construct()
    {
        parent::__construct();
        $this->semesterId = null;
    }

    public function getSemesterId(): ?int
    {
        return $this->semesterId;
    }

    public function setSemesterId(?int $semesterId): void
    {
        $this->semesterId = $semesterId;
    }

    public function toArray(): array
    {
        return [];
    }
}
