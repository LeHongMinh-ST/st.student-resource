<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use Illuminate\Http\UploadedFile;

class ImportCourseStudentDTO implements BaseDTO
{
    private int $facultyId;

    private UploadedFile $file;

    public function __construct()
    {
        $user = auth()->user();
        $this->setFacultyId($user->faculty_id ?? null);
    }

    public function getFacultyId(): int
    {
        return $this->facultyId;
    }

    public function setFacultyId(?int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }

    public function toArray(): array
    {
        return [
            'faculty_id' => $this->getFacultyId(),
            'file' => $this->getFile(),
        ];
    }
}
