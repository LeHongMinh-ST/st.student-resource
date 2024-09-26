<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\Enums\Gender;
use App\Enums\StudentRole;
use App\Enums\StudentStatus;
use App\Supports\PasswordHelper;
use App\Supports\SchoolHelper;
use App\Supports\StudentHelper;

class CreateStudentDTO implements BaseDTO
{
    private string $lastName;

    private string $firstName;

    private string $code;

    private string $email;

    private string $thumbnail;

    private ?int $facultyId;

    private string $password;

    private StudentStatus $status;

    private StudentRole $studentRole;

    private string $schoolYear;

    private int $admissionYear;

    private Gender $gender;

    public function __construct()
    {
        $user = auth()->user();
        $this->setFacultyId($user->faculty_id ?? null);
        $this->setPassword(PasswordHelper::makePassword());
        $this->setStatus(StudentStatus::CurrentlyStudying);
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

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function getFacultyId(): ?int
    {
        return $this->facultyId;
    }

    public function setFacultyId(?int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
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

    public function setSchoolYear(string $schoolYear): void
    {
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

    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function setGender(Gender $gender): void
    {
        $this->gender = $gender;
    }

    public function toArray(): array
    {
        return [
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'code' => $this->code,
            'email' => $this->email,
            'thumbnail' => $this->thumbnail,
            'faculty_id' => $this->facultyId,
            'password' => $this->password,
            'status' => $this->status->value,
            'role' => $this->studentRole->value,
            'school_year' => $this->schoolYear,
            'admission_year' => $this->admissionYear,
            'gender' => $this->gender->value,
        ];
    }
}
