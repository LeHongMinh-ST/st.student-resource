<?php

declare(strict_types=1);

namespace App\DTO\Student;

class CreateApproveStudentUpdateDTO
{
    private string $approveableType;
    private int $approveableId;
    private string $status;
    private string $note;
    private int $studentInfoUpdateId;

    public function __construct(
        string $approveableType = '',
        int $approveableId = 0,
        string $status = '',
        string $note = '',
        int $studentInfoUpdateId = 0
    ) {
        $this->approveableType = $approveableType;
        $this->approveableId = $approveableId;
        $this->status = $status;
        $this->note = $note;
        $this->studentInfoUpdateId = $studentInfoUpdateId;
    }

    public function getApproveableType(): string
    {
        return $this->approveableType;
    }

    public function setApproveableType(string $approveableType): void
    {
        $this->approveableType = $approveableType;
    }

    public function getApproveableId(): int
    {
        return $this->approveableId;
    }

    public function setApproveableId(int $approveableId): void
    {
        $this->approveableId = $approveableId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    public function getStudentInfoUpdateId(): int
    {
        return $this->studentInfoUpdateId;
    }

    public function setStudentInfoUpdateId(int $studentInfoUpdateId): void
    {
        $this->studentInfoUpdateId = $studentInfoUpdateId;
    }

    public function toArray(): array
    {
        return [
            'approveable_type' => $this->approveableType,
            'approveable_id' => $this->approveableId,
            'status' => $this->status,
            'note' => $this->note,
            'student_info_update_id' => $this->studentInfoUpdateId,
        ];
    }
}
