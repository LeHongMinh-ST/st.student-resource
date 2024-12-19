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

    private ?int $subTeacherId;

    private ?string $code;
    private ?int $facultyId;

    private ?string $type;

    public function __construct()
    {
        parent::__construct();
        $this->q = null;
        $this->status = null;
        $this->teacherId = null;
        $this->code = null;
        $this->facultyId = null;
        $this->type = 'teacher';
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        if ($type) {
            $this->type = $type;
        }
    }

    public function getFacultyId(): ?int
    {
        return $this->facultyId;
    }

    public function setFacultyId(?int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }

    public function getTeacherId(): ?int
    {
        return $this->teacherId;
    }

    public function setTeacherId(int|string|null $teacherId): void
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

    public function getCode(): ?string
    {
        return $this->q;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getSubTeacherId(): ?int
    {
        return $this->subTeacherId;
    }

    public function setSubTeacherId(int|string|null $subTeacherId): void
    {
        $this->subTeacherId = $subTeacherId;
    }


    public function toArray(): array
    {
        return [];
    }
}
