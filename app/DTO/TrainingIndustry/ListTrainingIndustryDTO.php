<?php

declare(strict_types=1);

namespace App\DTO\TrainingIndustry;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;
use App\Enums\Status;

class ListTrainingIndustryDTO extends BaseListDTO implements BaseDTO
{
    protected ?string $q;

    protected int $facultyId;

    protected ?Status $status;

    public function __construct()
    {
        parent::__construct();
        $this->q = null;
        $this->facultyId = auth()->user()->faculty_id;
        $this->status = null;
        $this->orderBy = 'id';
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): void
    {
        $this->status = $status;
    }

    public function getFacultyId(): int
    {
        return $this->facultyId;
    }

    public function setFacultyId(int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }

    public function getQ(): ?string
    {
        return $this->q;
    }

    public function setQ(?string $q): void
    {
        $this->q = $q;
    }

    public function toArray(): array
    {
        return [];
    }
}
