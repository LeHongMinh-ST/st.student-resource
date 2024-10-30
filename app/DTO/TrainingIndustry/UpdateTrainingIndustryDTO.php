<?php

declare(strict_types=1);

namespace App\DTO\TrainingIndustry;

use App\Enums\Status;

class UpdateTrainingIndustryDTO
{
    private ?int $id;

    private ?string $name;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
    private ?string $description;

    public function __construct()
    {
        $this->id = null;
        $this->name = null;
        $this->status = null;
        $this->description = null;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): void
    {
        $this->status = $status;
    }
    private ?Status $status;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    // Method to convert the object to an array
    public function toArray(): array
    {
        $array = [
            'name' => $this->getName(),
            'status' => $this->getStatus(),
            'description' => $this->getDescription(),
        ];

        return array_filter($array, fn ($value) => null !== $value);
    }
}
