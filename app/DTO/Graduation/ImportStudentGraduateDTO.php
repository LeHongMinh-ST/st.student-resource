<?php

namespace App\DTO\Graduation;

use Illuminate\Http\UploadedFile;

class ImportStudentGraduateDTO
{
    private UploadedFile $file;

    private int $graduationCeremoniesId;

    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }

    public function getGraduationCeremoniesId(): int
    {
        return $this->graduationCeremoniesId;
    }

    public function setGraduationCeremoniesId(int $graduationCeremoniesId): void
    {
        $this->graduationCeremoniesId = $graduationCeremoniesId;
    }
}
