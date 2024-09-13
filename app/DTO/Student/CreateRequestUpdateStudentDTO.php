<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\Enums\SocialPolicyObject;
use App\Enums\TrainingType;
use InvalidArgumentException;

class CreateRequestUpdateStudentDTO implements BaseDTO
{
    private int $studentId;

    private string $personEmail;

    private string $gender;

    private string $permanentResidence;

    private string $dob;

    private string $pob;

    private string $countryside;

    private string $address;

    private TrainingType $trainingType;

    private string $phone;

    private string $nationality;

    private string $citizenIdentification;

    private string $ethnic;

    private string $religion;

    private SocialPolicyObject $socialPolicyObject;

    private string $note;

    private array $family;

    public function getFamily(): array
    {
        return $this->family;
    }

    public function setFamily(array $family): void
    {
        foreach ($family as $member) {
            if (! $member instanceof CreateRequestUpdateFamilyStudentDTO) {
                throw new InvalidArgumentException('Member must be an instance of CreateRequestUpdateFamilyStudentDTO');
            }
        }

        $this->family = $family;
    }

    public function getStudentId(): int
    {
        return $this->studentId;
    }

    public function setStudentId(int $studentId): void
    {
        $this->studentId = $studentId;
    }

    public function getPersonEmail(): string
    {
        return $this->personEmail;
    }

    public function setPersonEmail(string $personEmail): void
    {
        $this->personEmail = $personEmail;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getPermanentResidence(): string
    {
        return $this->permanentResidence;
    }

    public function setPermanentResidence(string $permanentResidence): void
    {
        $this->permanentResidence = $permanentResidence;
    }

    public function getDob(): string
    {
        return $this->dob;
    }

    public function setDob(string $dob): void
    {
        $this->dob = $dob;
    }

    public function getPob(): string
    {
        return $this->pob;
    }

    public function setPob(string $pob): void
    {
        $this->pob = $pob;
    }

    public function getCountryside(): string
    {
        return $this->countryside;
    }

    public function setCountryside(string $countryside): void
    {
        $this->countryside = $countryside;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getTrainingType(): TrainingType
    {
        return $this->trainingType;
    }

    public function setTrainingType(TrainingType $trainingType): void
    {
        $this->trainingType = $trainingType;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getNationality(): string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): void
    {
        $this->nationality = $nationality;
    }

    public function getCitizenIdentification(): string
    {
        return $this->citizenIdentification;
    }

    public function setCitizenIdentification(string $citizenIdentification): void
    {
        $this->citizenIdentification = $citizenIdentification;
    }

    public function getEthnic(): string
    {
        return $this->ethnic;
    }

    public function setEthnic(string $ethnic): void
    {
        $this->ethnic = $ethnic;
    }

    public function getReligion(): string
    {
        return $this->religion;
    }

    public function setReligion(string $religion): void
    {
        $this->religion = $religion;
    }

    public function getSocialPolicyObject(): SocialPolicyObject
    {
        return $this->socialPolicyObject;
    }

    public function setSocialPolicyObject(SocialPolicyObject $socialPolicyObject): void
    {
        $this->socialPolicyObject = $socialPolicyObject;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    public function toArray(): array
    {
        return array_filter([
            'person_email' => $this->personEmail,
            'gender' => $this->gender,
            'permanent_residence' => $this->permanentResidence,
            'dob' => $this->dob,
            'pob' => $this->pob,
            'countryside' => $this->countryside,
            'address' => $this->address,
            'training_type' => $this->trainingType->value,
            'phone' => $this->phone,
            'nationality' => $this->nationality,
            'citizen_identification' => $this->citizenIdentification,
            'ethnic' => $this->ethnic,
            'religion' => $this->religion,
            'social_policy_object' => $this->socialPolicyObject->value,
            'note' => $this->note,
        ], fn ($value) => null !== $value);
    }
}
