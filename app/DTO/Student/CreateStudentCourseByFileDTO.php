<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\Enums\StudentRole;
use App\Enums\StudentStatus;
use App\Supports\PasswordHelper;
use App\Supports\StudentHelper;
use InvalidArgumentException;

class CreateStudentCourseByFileDTO implements BaseDTO
{
    private CreateStudentInfoByFileImportDTO $studentInfoDTO;

    private array $family;

    private string $lastName;

    private string $firstName;

    private string $code;

    private ?int $facultyId;

    private string $password;

    private StudentStatus $status;

    private StudentRole $studentRole;

    private int $admissionYearId;

    public function __construct()
    {
        $user = auth()->user();
        $this->setFacultyId($user->faculty_id ?? null);
        $this->setPassword(PasswordHelper::makePassword());
        $this->setStatus(StudentStatus::CurrentlyStudying);
        $this->setFamily([]);
    }

    public function getFamily(): array
    {
        return $this->family;
    }

    public function setFamily(array $family): void
    {
        foreach ($family as $member) {
            if (! $member instanceof CreateFamilyStudentDTO) {
                throw new InvalidArgumentException('Member must be an instance of UpdateRequestUpdateFamilyStudentDTO');
            }
        }

        $this->family = $family;
    }

    public function getStudentInfoDTO(): CreateStudentInfoByFileImportDTO
    {
        return $this->studentInfoDTO;
    }

    public function setStudentInfoDTO(CreateStudentInfoByFileImportDTO $studentInfoDTO): void
    {
        $this->studentInfoDTO = $studentInfoDTO;
    }

    public function getFamilyStudentDTO(): UpdateRequestUpdateFamilyStudentDTO
    {
        return $this->familyStudentDTO;
    }

    public function setFamilyStudentDTO(UpdateRequestUpdateFamilyStudentDTO $familyStudentDTO): void
    {
        $this->familyStudentDTO = $familyStudentDTO;
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

    public function getAdmissionYearId(): int
    {
        return $this->admissionYearId;
    }

    public function setAdmissionYearId(int $admissionYearId): void
    {
        $this->admissionYearId = $admissionYearId;
    }

    public function toArray(): array
    {
        return [
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'code' => $this->code,
            'email' => $this->email,
            'faculty_id' => $this->facultyId,
            'password' => $this->password,
            'status' => $this->status->value,
            'student_role' => $this->studentRole->value,
            'admission_year_id' => $this->admissionYearId,
        ];
    }
}
