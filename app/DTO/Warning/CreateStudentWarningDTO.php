<?php

declare(strict_types=1);

namespace App\DTO\Warning;

class CreateStudentWarningDTO
{
    private string $name;
    private int $semesterId;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSemesterId(): int
    {
        return $this->semesterId;
    }

    public function setSemesterId(int $semesterId): void
    {
        $this->semesterId = $semesterId;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'semester_id' => $this->semesterId,
        ];
    }
}
