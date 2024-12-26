<?php

declare(strict_types=1);

namespace App\DTO\Student;

use App\DTO\BaseDTO;
use App\Enums\StudentInfoUpdateStatus;

class ChangeStatusRequestUpdateStudentDTO implements BaseDTO
{
    private int $studentInfoUpdateId;

    private StudentInfoUpdateStatus $status;

    private string $rejectNote;

    public function __construct(
        int $studentInfoUpdateId,
        StudentInfoUpdateStatus $status,
        string $rejectNote
    ) {
        $this->studentInfoUpdateId = $studentInfoUpdateId;
        $this->status = $status;
        $this->rejectNote = $rejectNote;
    }

    public function getStudentInfoUpdateId(): int
    {
        return $this->studentInfoUpdateId;
    }

    public function setStudentInfoUpdateId(int $studentInfoUpdateId): void
    {
        $this->studentInfoUpdateId = $studentInfoUpdateId;
    }

    public function getStatus(): StudentInfoUpdateStatus
    {
        return $this->status;
    }

    public function setStatus(StudentInfoUpdateStatus $status): void
    {
        $this->status = $status;
    }

    public function getRejectNote(): string
    {
        return $this->rejectNote;
    }

    public function setRejectNote(string $rejectNote): void
    {
        $this->rejectNote = $rejectNote;
    }

    public function toArray(): array
    {
        return [
            'student_info_update_id' => $this->studentInfoUpdateId,
            'status' => $this->status->value,
            'reject_note' => $this->rejectNote,
        ];
    }
}
