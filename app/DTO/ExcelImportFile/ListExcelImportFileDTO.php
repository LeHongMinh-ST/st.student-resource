<?php

declare(strict_types=1);

namespace App\DTO\ExcelImportFile;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;
use App\Enums\ExcelImportType;

class ListExcelImportFileDTO extends BaseListDTO implements BaseDTO
{
    private ?ExcelImportType $type;

    private ?int $typeId;
    private ?int $facultyId;

    public function __construct()
    {
        $this->type = null;
        $this->typeId = null;
        $this->facultyId = null;
        parent::__construct();
    }

    public function getFacultyId(): ?int
    {
        return $this->facultyId;
    }

    public function setFacultyId(?int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }


    public function getType(): ?ExcelImportType
    {
        return $this->type;
    }

    public function setType(?ExcelImportType $type): void
    {
        $this->type = $type;
    }

    public function getTypeId(): ?int
    {
        return $this->typeId;
    }

    public function setTypeId(?int $typeId): void
    {
        $this->typeId = $typeId;
    }

    public function toArray(): array
    {
        return [];
    }
}
