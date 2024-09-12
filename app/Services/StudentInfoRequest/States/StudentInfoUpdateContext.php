<?php

declare(strict_types=1);

namespace App\Services\StudentInfoRequest\States;

use App\Exceptions\UpdateResourceFailedException;
use App\Models\StudentInfoUpdate;
use App\Services\StudentInfoRequest\StudentInfoUpdateService;

/**
 * The Context defines the interface of interest to clients. It also maintains a
 * reference to an instance of a State subclass, which represents the current
 * state of the Context.
 */
class StudentInfoUpdateContext
{
    /**
     * @var StudentInfoUpdateState A reference to the current state of the Context.
     */
    private StudentInfoUpdateState $state;

    private StudentInfoUpdate $studentInfoUpdate;

    private ?string $rejectNote;

    public function __construct(StudentInfoUpdateState $state, StudentInfoUpdate $studentInfoUpdate, ?string $rejectNote)
    {
        $this->studentInfoUpdate = $studentInfoUpdate;
        $this->transitionTo($state);
        $this->rejectNote = $rejectNote;
    }

    /**
     * The Context allows changing the State object at runtime.
     */
    public function transitionTo(StudentInfoUpdateState $state): void
    {
        $this->state = $state;
        $this->state->setContext($this);
    }

    /**
     * The Context delegates part of its behavior to the current State object.
     */
    public function handleApproval(): void
    {
        $this->state->handleApproval();
    }

    /**
     * @throws UpdateResourceFailedException
     */
    public function handleRejection(): void
    {
        $this->state->handleRejection();
    }

    public function getStudentInfoUpdate(): StudentInfoUpdate
    {
        return $this->studentInfoUpdate;
    }

    public function createApprovalUser(int $userId, string $userType): void
    {
        $studentInfoUpdate = $this->getStudentInfoUpdate();
        app()->make(StudentInfoUpdateService::class)->create([
            'approveable_type' => $userType,
            'approveable_id' => $userId,
            'status' => $studentInfoUpdate->status,
            'note' => $this->rejectNote,
            'student_info_update_id' => $studentInfoUpdate->id,
        ]);
    }
}
