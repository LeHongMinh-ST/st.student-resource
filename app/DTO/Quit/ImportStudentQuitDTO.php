<?php

declare(strict_types=1);

namespace App\DTO\Quit;

use Illuminate\Http\UploadedFile;

class ImportStudentQuitDTO
{
    private UploadedFile $file;

    private int $quitId;

    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }

    public function getQuitId(): int
    {
        return $this->quitId;
    }

    public function setQuitId(int $quitId): void
    {
        $this->quitId = $quitId;
    }
}
