<?php

declare(strict_types=1);

namespace App\DTO\GeneralClass;

use App\Enums\ClassType;
use App\Enums\Status;
use Illuminate\Support\Arr;

class CreateGeneralClassDTO
{
    private ?string $name;

    private ?string $code;

    private ?string $status;

    private ?int $facultyId;

    private ?int $majorId;

    private ?int $teacherId;

    private ?string $type;

    public function __construct(array $data = [])
    {
        if (! empty($data)) {
            $this->name = Arr::get($data, 'name');
            $this->code = Arr::get($data, 'code');
            $this->status = Arr::get($data, 'status', Status::Enable->value);
            $this->facultyId = Arr::get($data, 'faculty_id');
            $this->majorId = Arr::get($data, 'major_id');
            $this->teacherId = Arr::get($data, 'teacher_id');
            $this->type = Arr::get($data, 'type', ClassType::Basic->value);
        } else {
            $this->name = null;
            $this->code = null;
            $this->status = null;
            $this->facultyId = null;
            $this->majorId = null;
            $this->teacherId = null;
            $this->type = null;
        }
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

    public function getFacultyId(): ?int
    {
        return $this->facultyId;
    }

    public function setFacultyId(?int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }

    public function getMajorId(): ?int
    {
        return $this->majorId;
    }

    public function setMajorId(?int $majorId): void
    {
        $this->majorId = $majorId;
    }

    public function getTeacherId(): ?int
    {
        return $this->teacherId;
    }

    public function setTeacherId(?int $teacherId): void
    {
        $this->teacherId = $teacherId;
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
        return [
            'name' => $this->name,
            'code' => $this->code,
            'status' => $this->status,
            'faculty_id' => $this->facultyId,
            'major_id' => $this->majorId,
            'teacher_id' => $this->teacherId,
            'type' => $this->type,
        ];
    }
}
