<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\Enums\StudentRole;
use App\Enums\StudentStatus;
use App\Supports\SchoolHelper;
use App\Supports\StudentHelper;

class UpdateStudentDTO implements BaseDTO
{
    private int $id;

    private ?string $lastName;

    private ?string $firstName;

    private ?string $code;

    private ?string $email;

    private ?int $facultyId;

    private ?StudentStatus $status;

    private ?StudentRole $studentRole;

    private ?string $schoolYear;

    private ?int $admissionYear;

    private UpdateStudentInfoDTO $infoDTO;

    public function __construct()
    {
        $this->infoDTO = new UpdateStudentInfoDTO();
        $this->facultyId = null;
        $this->status = null;
        $this->studentRole = null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFullName(): string
    {
        return $this->lastName . ' ' . $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
        $this->email = StudentHelper::makeEmailStudent($code);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFacultyId(): ?int
    {
        return $this->facultyId;
    }

    public function setFacultyId(?int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }

    public function getStatus(): StudentStatus
    {
        return $this->status;
    }

    public function setStatus(StudentStatus $status): void
    {
        $this->status = $status;
    }

    public function getStudentRole(): StudentRole
    {
        return $this->studentRole;
    }

    public function setStudentRole(StudentRole $studentRole): void
    {
        $this->studentRole = $studentRole;
    }

    public function getSchoolYear(): string
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(?string $schoolYear): void
    {
        if (null === $schoolYear) {
            $this->schoolYear = null;
            $this->admissionYear = null;

            return;
        }
        $this->schoolYear = $schoolYear;
        [$startYear, $endYear] = SchoolHelper::getStartEndYearInSchoolYear($schoolYear);
        $this->admissionYear = SchoolHelper::calculateAdmissionYear($startYear);
    }

    public function getAdmissionYear(): int
    {
        return $this->admissionYear;
    }

    public function setAdmissionYear(int $admissionYear): void
    {
        $this->admissionYear = $admissionYear;
    }

    public function setInfoDTO(UpdateStudentInfoDTO $updateStudentInfoCommand): void
    {
        $this->infoDTO = $updateStudentInfoCommand;
    }

    public function getInfoDTO(): UpdateStudentInfoDTO
    {
        return $this->infoDTO;
    }

    public function toArray(): array
    {
        return array_filter([
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'code' => $this->code,
            'email' => $this->email,
            'faculty_id' => $this->facultyId,
            'status' => $this->status?->value,
            'role' => $this->studentRole?->value,
            'admission_year' => $this->admissionYear,
        ], fn ($value) => null !== $value);
    }

    public function toInfoArray(): array
    {
        return $this->getInfoCommand()->toArray();
    }
}
