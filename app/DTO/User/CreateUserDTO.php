<?php

declare(strict_types=1);

namespace App\DTO\User;

use App\Enums\UserRole;

class CreateUserDTO
{
    private string $userName;

    private string $firstName;

    private string $lastName;

    private ?string $phone;

    private string $email;

    private string $password;

    private int $facultyId;

    private ?string $code;

    private ?string $thumbnail;

    private ?int $departmentId;

    private ?bool $isSuperAdmin;

    private ?UserRole $role;

    public function __construct()
    {
        $this->phone = null;
        $this->code = null;
        $this->thumbnail = null;
        $this->departmentId = null;
        $this->isSuperAdmin = false;
    }

    // Getter and Setter for userName
    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    // Getter and Setter for firstName
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    // Getter and Setter for lastName
    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    // Getter and Setter for phone
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    // Getter and Setter for email
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    // Getter and Setter for password
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    // Getter and Setter for facultyId
    public function getFacultyId(): int
    {
        return $this->facultyId;
    }

    public function setFacultyId(int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }

    // Getter and Setter for code
    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    // Getter and Setter for thumbnail
    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    // Getter and Setter for departmentId
    public function getDepartmentId(): ?int
    {
        return $this->departmentId;
    }

    public function setDepartmentId(?int $departmentId): void
    {
        $this->departmentId = $departmentId;
    }

    // Getter and Setter for role
    public function getRole(): ?UserRole
    {
        return $this->role;
    }

    public function setRole(?UserRole $role): void
    {
        $this->role = $role;
    }

    public function getIsSuperAdmin(): ?bool
    {
        return $this->isSuperAdmin;
    }

    public function setIsSuperAdmin(?bool $isSuperAdmin): void
    {
        $this->isSuperAdmin = $isSuperAdmin;
    }

    // Method to convert the object to an array
    public function toArray(): array
    {
        return [
            'user_name' => $this->userName,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'email' => $this->email,
            'password' => $this->password,
            'faculty_id' => $this->facultyId,
            'code' => $this->code,
            'thumbnail' => $this->thumbnail,
            'department_id' => $this->departmentId,
            'role' => $this->role?->value,
            'is_super_admin' => $this->isSuperAdmin,
        ];
    }
}
