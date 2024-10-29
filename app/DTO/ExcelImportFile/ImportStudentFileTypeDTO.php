<?php

declare(strict_types=1);

namespace App\DTO\ExcelImportFile;

use App\Enums\ExcelImportType;
use Illuminate\Http\UploadedFile;

class ImportStudentFileTypeDTO
{
    private UploadedFile $file;

    private ExcelImportType $type;

    private int $typeId;

    private int $facultyId;

    public function __construct() {}
    public function getFacultyId(): int
    {
        return $this->facultyId;
    }

    public function setFacultyId(int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }

    public function getType(): ExcelImportType
    {
        return $this->type;
    }

    public function setType(ExcelImportType $type): void
    {
        $this->type = $type;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function setTypeId(int $typeId): void
    {
        $this->typeId = $typeId;
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }
}
