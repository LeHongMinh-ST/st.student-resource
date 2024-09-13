<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\Enums\Gender;
use App\Enums\SocialPolicyObject;
use App\Enums\TrainingType;
use Carbon\Carbon;

class UpdateStudentInfoDTO implements BaseDTO
{
    private ?int $id;
    private ?string $personEmail;
    private ?Gender $gender;
    private ?string $permanentResidence;
    private ?string $dob;
    private ?string $pob;
    private ?string $countryside;
    private ?string $address;
    private ?TrainingType $trainingType;
    private ?string $phone;
    private ?string $nationality;
    private ?string $citizenIdentification;
    private ?string $ethnic;
    private ?string $religion;
    private ?string $thumbnail;
    private ?SocialPolicyObject $socialPolicyObject;
    private ?string $note;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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

    public function getPermanentResidence(): ?string
    {
        return $this->permanentResidence;
    }

    public function setPermanentResidence(?string $permanentResidence): void
    {
        $this->permanentResidence = $permanentResidence;
    }

    public function getDob(): ?string
    {
        return $this->dob;
    }

    public function setDob(?string $dob): void
    {
        $this->dob = $dob;
    }

    public function getPob(): ?string
    {
        return $this->pob;
    }

    public function setPob(?string $pob): void
    {
        $this->pob = $pob;
    }

    public function getCountryside(): ?string
    {
        return $this->countryside;
    }

    public function setCountryside(?string $countryside): void
    {
        $this->countryside = $countryside;
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

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): void
    {
        $this->nationality = $nationality;
    }

    public function getCitizenIdentification(): ?string
    {
        return $this->citizenIdentification;
    }

    public function setCitizenIdentification(?string $citizenIdentification): void
    {
        $this->citizenIdentification = $citizenIdentification;
    }

    public function getEthnic(): ?string
    {
        return $this->ethnic;
    }

    public function setEthnic(?string $ethnic): void
    {
        $this->ethnic = $ethnic;
    }

    public function getReligion(): ?string
    {
        return $this->religion;
    }

    public function setReligion(?string $religion): void
    {
        $this->religion = $religion;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function getSocialPolicyObject(): ?SocialPolicyObject
    {
        return $this->socialPolicyObject;
    }

    public function setSocialPolicyObject(?SocialPolicyObject $socialPolicyObject): void
    {
        $this->socialPolicyObject = $socialPolicyObject;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    public function toArray(): array
    {
        return array_filter([
            'person_email' => $this->getPersonEmail(),
            'gender' => $this->getGender(),
            'permanent_residence' => $this->getPermanentResidence(),
            'dob' => $this->getDob() ? Carbon::createFromFormat('d-m-Y', $this->getDob()) : null,
            'pob' => $this->getPob(),
            'countryside' => $this->getCountryside(),
            'address' => $this->getAddress(),
            'training_type' => $this->getTrainingType(),
            'phone' => $this->getPhone(),
            'nationality' => $this->getNationality(),
            'citizen_identification' => $this->getCitizenIdentification(),
            'ethnic' => $this->getEthnic(),
            'religion' => $this->getReligion(),
            'thumbnail' => $this->getThumbnail(),
            'social_policy_object' => $this->getSocialPolicyObject(),
            'note' => $this->getNote(),
        ], fn ($value) => null !== $value);
    }
}
