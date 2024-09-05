<?php

declare(strict_types=1);

namespace App\DTO\GeneralClass;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;

class ListGeneralClassDTO extends BaseListDTO implements BaseDTO
{
    private ?string $q;
    private ?string $status;
    private ?int $teacherId;

    public function __construct()
    {
        parent::__construct();
        $this->q = null;
        $this->status = null;
        $this->teacherId = null;
    }

    public function getTeacherId(): ?int
    {
        return $this->teacherId;
    }

    public function setTeacherId(?int $teacherId): void
    {
        $this->teacherId = $teacherId;
    }

    public function getQ(): ?string
    {
        return $this->q;
    }

    public function setQ(?string $q): void
    {
        $this->q = $q;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [];
    }
}
