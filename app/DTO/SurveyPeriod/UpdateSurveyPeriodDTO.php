<?php

declare(strict_types=1);

namespace App\DTO\SurveyPeriod;

use App\Enums\Status;
use DateTime;

class UpdateSurveyPeriodDTO
{
    private ?int $id;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    private ?string $title;
    private ?DateTime $startDate;
    private ?DateTime $endDate;
    private ?string $description;
    private ?Status $status;

    public function getGraduationCeremonyIds(): ?array
    {
        return $this->graduationCeremonyIds;
    }

    public function setGraduationCeremonyIds(?array $graduationCeremonyIds): void
    {
        $this->graduationCeremonyIds = $graduationCeremonyIds;
    }
    private ?array $graduationCeremonyIds;

    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?int $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }
    private ?int $updatedBy;

    public function __construct()
    {
        $this->id = null;
        $this->title = null;
        $this->startDate = null;
        $this->endDate = null;
        $this->status = null;
        $this->description = null;
        $this->graduationCeremonyIds = null;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): void
    {
        $this->status = $status;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Method to convert the object to an array
    public function toArray(): array
    {
        $array = [
            'title' => $this->getTitle(),
            'start_time' => $this->getStartDate()?->format('Y-m-d H:i:s'),
            'end_time' => $this->getEndDate()?->format('Y-m-d H:i:s'),
            'description' => $this->getDescription(),
            'status' => $this->getStatus(),
            'updated_by' => $this->getUpdatedBy(),
            'graduation_ceremony_ids' => $this->getGraduationCeremonyIds(),
        ];

        return array_filter($array, fn ($value) => null !== $value);
    }
}
