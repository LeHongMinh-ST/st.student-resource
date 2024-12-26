<?php

declare(strict_types=1);

namespace App\DTO\Faculty;

class CreateFacultyDTO
{
    private string $name;

    private string $code;

    private ?string $emailUser;

    public function getEmailUser(): ?string
    {
        return $this->emailUser;
    }

    public function setEmailUser(?string $emailUser): void
    {
        $this->emailUser = $emailUser;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'email' => $this->emailUser,
        ];
    }
}
