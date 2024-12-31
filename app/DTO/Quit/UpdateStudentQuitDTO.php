<?php

declare(strict_types=1);

namespace App\DTO\Quit;

class UpdateStudentQuitDTO
{
    private int|string $id;
    private string $name;
    private ?int $year;
    private ?string $certification;
    private ?string $certificationDate;

    public function __construct()
    {
        $this->name = "";
        $this->year = null;
        $this->certification = null;
        $this->certificationDate = null;
    }

    public function getId(): int
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): void
    {
        $this->year = $year;
    }

    public function getCertification(): ?string
    {
        return $this->certification;
    }

    public function setCertification(?string $certification): void
    {
        $this->certification = $certification;
    }

    public function getCertificationDate(): ?string
    {
        return $this->certificationDate;
    }

    public function setCertificationDate(?string $certificationDate): void
    {
        $this->certificationDate = $certificationDate;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'year' => $this->year,
            'certification' => $this->certification,
            'certification_date' => $this->certificationDate,
        ];
    }

}
