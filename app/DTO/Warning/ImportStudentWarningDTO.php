<?php

declare(strict_types=1);

namespace App\DTO\Warning;

use Illuminate\Http\UploadedFile;

class ImportStudentWarningDTO
{
    private UploadedFile $file;

    private int $warningId;

    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }

    public function getWarningId(): int
    {
        return $this->warningId;
    }

    public function setWarningId(int $warningId): void
    {
        $this->warningId = $warningId;
    }
}
