<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use Illuminate\Http\UploadedFile;

class ImportCourseStudentDTO implements BaseDTO
{
    private int $facultyId;

    private int $admissionYearId;

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

    public function getAdmissionYearId(): int
    {
        return $this->admissionYearId;
    }

    public function setAdmissionYearId(int $admissionYearId): void
    {
        $this->admissionYearId = $admissionYearId;
    }



    public function toArray(): array
    {
        return [
            'faculty_id' => $this->getFacultyId(),
            'file' => $this->getFile(),
            'admission_year_id' => $this->admissionYearId
        ];
    }
}
