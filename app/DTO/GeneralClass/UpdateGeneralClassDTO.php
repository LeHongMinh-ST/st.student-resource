<?php

declare(strict_types=1);

namespace App\DTO\GeneralClass;

class UpdateGeneralClassDTO
{
    private ?int $id;

    private ?string $name;

    private ?string $code;

    private ?string $status;

    private ?int $trainingIndustryId;

    private ?int $teacherId;

    private ?int $subTeacherId;

    private ?string $type;

    private ?int $studentPresidentId;

    private ?int $studentSecretaryId;

    public function __construct()
    {
        $this->id = null;
        $this->name = null;
        $this->code = null;
        $this->status = null;
        $this->trainingIndustryId = null;
        $this->type = null;
        $this->teacherId = null;
        $this->subTeacherId = null;
        $this->studentPresidentId = null;
        $this->studentSecretaryId = null;
    }

    public function getStudentPresidentId(): ?int
    {
        return $this->studentPresidentId;
    }

    public function getStudentSecretaryId(): ?int
    {
        return $this->studentSecretaryId;
    }

    public function setStudentPresidentId(int $studentPresidentId): void
    {
        $this->studentPresidentId = $studentPresidentId;
    }

    public function setStudentSecretaryId(int $studentSecretaryId): void
    {
        $this->studentSecretaryId = $studentSecretaryId;
    }

    public function getSubTeacherId(): ?int
    {
        return $this->subTeacherId;
    }

    public function setSubTeacherId(?int $subTeacherId): void
    {
        $this->subTeacherId = $subTeacherId;
    }

    public function getTeacherId(): ?int
    {
        return $this->teacherId;
    }

    public function setTeacherId(?int $teacherId): void
    {
        $this->teacherId = $teacherId;
    }

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getTrainingIndustryId(): ?int
    {
        return $this->trainingIndustryId;
    }

    public function setTrainingIndustryId(?int $trainingIndustryId): void
    {
        $this->trainingIndustryId = $trainingIndustryId;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    // Method to convert the object to an array
    public function toArray(): array
    {
        $generalClassArray = [
            'name' => $this->name,
            'code' => $this->code,
            'status' => $this->status,
            'training_industry_id' => $this->trainingIndustryId,
            'type' => $this->type,
            'teacher_id' => $this->teacherId,
            'sub_teacher_id' => $this->subTeacherId,
        ];

        return array_filter($generalClassArray, fn ($value) => null !== $value);
    }
}
