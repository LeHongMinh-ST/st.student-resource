<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\Enums\Gender;
use App\Enums\TrainingType;
use App\Supports\DateTimeHelper;

class CreateStudentInfoByFileImportDTO implements BaseDTO
{
    private ?string $personEmail;

    private ?Gender $gender;

    private ?string $dob;

    private ?string $address;

    private ?TrainingType $trainingType;

    private ?string $phone;

    private ?string $ethnic;
    private ?string $thumbnail;

    public function getPersonEmail(): ?string
    {
        return $this->personEmail;
    }

    public function setPersonEmail(?string $personEmail): void
    {
        $this->personEmail = $personEmail;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): void
    {
        $this->gender = $gender;
    }

    public function getDob(): ?string
    {
        return $this->dob;
    }

    // Set dob with format d/m/Y
    public function setDob(?string $dob): void
    {
        $this->dob = $dob;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getTrainingType(): ?TrainingType
    {
        return $this->trainingType;
    }

    public function setTrainingType(?TrainingType $trainingType): void
    {
        $this->trainingType = $trainingType;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function setEthnic(?string $ethnic): void
    {
        $this->ethnic = $ethnic;
    }

    public function getEthnic(): ?string
    {
        return $this->ethnic;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function toArray(): array
    {
        return array_filter([
            'person_email' => $this->getPersonEmail(),
            'gender' => $this->getGender(),
            'dob' => $this->getDob() ? DateTimeHelper::createDateTime($this->getDob()) : null,
            'address' => $this->getAddress(),
            'training_type' => $this->getTrainingType(),
            'phone' => $this->getPhone(),
            'ethnic' => $this->getEthnic(), // Dân tộc
            'thumbnail' => $this->getThumbnail(),
        ], fn ($value) => null !== $value);
    }
}
