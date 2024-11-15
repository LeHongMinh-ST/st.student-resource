<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\Enums\Gender;
use App\Enums\TrainingType;
use App\Supports\DateTimeHelper;
use Illuminate\Support\Arr;

class UpdateStudentInfoByFileGraduationDTO implements BaseDTO
{
    private ?string $personEmail;

    private ?Gender $gender;

    private ?string $dob;

    private ?string $address;

    private ?TrainingType $trainingType;

    private ?string $phone;

    private ?string $citizenIdentification;
    public function __construct(array $data)
    {
        $this->personEmail = Arr::get($data, 'person_email');
        $this->gender = Arr::get($data, 'gender') ? Gender::mapValue($data['gender']) : null;
        $this->dob = Arr::get($data, 'dob');
        $this->address = Arr::get($data, 'address');
        $this->trainingType = TrainingType::FormalUniversity;
        $this->phone = Arr::get($data, 'phone_number');
        $this->citizenIdentification = Arr::get($data, 'citizen_identification');
    }

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

    public function getCitizenIdentification(): ?string
    {
        return $this->citizenIdentification;
    }

    public function setCitizenIdentification(?string $citizenIdentification): void
    {
        $this->citizenIdentification = $citizenIdentification;
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
            'citizen_identification' => $this->getCitizenIdentification(),
        ], fn ($value) => null !== $value);
    }
}
