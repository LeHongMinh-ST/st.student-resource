<?php

declare(strict_types=1);

namespace App\DTO\GeneralClass;

class UpdateGeneralClassDTO
{
    private ?int $id;

    private ?string $name;

    private ?string $code;

    private ?string $status;

    private ?int $majorId;

    private ?int $teacherId;

    private ?int $subTeacherId;

    private ?string $type;

    public function __construct()
    {
        $this->id = null;
        $this->name = null;
        $this->code = null;
        $this->status = null;
        $this->majorId = null;
        $this->type = null;
        $this->teacherId = null;
        $this->subTeacherId = null;
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

    public function getMajorId(): ?int
    {
        return $this->majorId;
    }

    public function setMajorId(?int $majorId): void
    {
        $this->majorId = $majorId;
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
            'major_id' => $this->majorId,
            'type' => $this->type,
            'teacher_id' => $this->teacherId,
        ];

        return array_filter($generalClassArray, fn ($value) => null !== $value);
    }
}
