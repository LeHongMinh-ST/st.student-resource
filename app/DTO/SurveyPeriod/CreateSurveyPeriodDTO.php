<?php

declare(strict_types=1);

namespace App\DTO\SurveyPeriod;

use App\Enums\Status;
use App\Enums\SurveyPeriodType;
use DateTime;

class CreateSurveyPeriodDTO
{
    private string $title;

    private DateTime $startDate;

    private DateTime $endDate;

    private Status $status;

    private ?string $description;

    private int $facultyId;
    private array $graduationCeremonyIds;
    private int $createdBy;
    private int $updatedBy;

    private string $year;

    private SurveyPeriodType $type;

    public function __construct()
    {
        $this->description = null;
    }

    public function getGraduationCeremonyIds(): array
    {
        return $this->graduationCeremonyIds;
    }

    public function setGraduationCeremonyIds(array $graduationCeremonyIds): void
    {
        $this->graduationCeremonyIds = $graduationCeremonyIds;
    }

    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getUpdatedBy(): int
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(int $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    public function getYear(): string
    {
        return $this->year;
    }

    public function setYear(string $year): void
    {
        $this->year = $year;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getFacultyId(): int
    {
        return $this->facultyId;
    }

    public function setFacultyId(int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }

    public function getType(): SurveyPeriodType
    {
        return $this->type;
    }

    public function setType(SurveyPeriodType $type): void
    {
        $this->type = $type;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'start_time' => $this->getStartDate()->format('Y-m-d H:i:s'),
            'end_time' => $this->getEndDate()->format('Y-m-d H:i:s'),
            'status' => $this->getStatus(),
            'description' => $this->getDescription(),
            'faculty_id' => $this->getFacultyId(),
            'graduation_ceremony_ids' => $this->getGraduationCeremonyIds(),
            'type' => $this->getType(),
            'year' => $this->getYear(),
            'created_by' => $this->getCreatedBy(),
            'updated_by' => $this->getUpdatedBy(),
        ];
    }
}
