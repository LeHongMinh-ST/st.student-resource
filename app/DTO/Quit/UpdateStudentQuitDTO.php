<?php

declare(strict_types=1);

namespace App\DTO\Quit;

class UpdateStudentQuitDTO
{
    private int|string $id;
    private string $name;
    private int $semesterId;

    public function getId(): int|string
    {
        return $this->id;
    }

    public function setId(int|string $id): void
    {
        $this->id = $id;
    }

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
