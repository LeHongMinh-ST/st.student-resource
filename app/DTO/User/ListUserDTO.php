<?php

declare(strict_types=1);

namespace App\DTO\User;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;
use App\Enums\UserRole;

class ListUserDTO extends BaseListDTO implements BaseDTO
{
    private ?string $q;

    private ?string $status;

    private ?int $departmentId;

    private ?int $facultyId;

    private UserRole $userRole;

    public function __construct()
    {
        parent::__construct();
        $this->q = null;
        $this->status = null;
        $this->departmentId = null;
    }

    public function getDepartmentId(): ?int
    {
        return $this->departmentId;
    }

    public function getFacultyId(): ?int
    {
        return $this->facultyId;
    }

    public function setDepartmentId(?int $departmentId): void
    {
        $this->departmentId = $departmentId;
    }

    public function setFacultyId(?int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }

    public function getQ(): ?string
    {
        return $this->q;
    }

    public function setQ(?string $q): void
    {
        $this->q = $q;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getUserRole(): UserRole
    {
        return $this->userRole;
    }

    public function setUserRole(UserRole $userRole): void
    {
        $this->userRole = $userRole;
    }

    public function toArray(): array
    {
        return [];
    }
}
