<?php

declare(strict_types=1);

namespace App\Services\StudentInfoRequest\States;

use App\Enums\AuthApiSection;
use App\Enums\StudentInfoUpdateStatus;
use App\Exceptions\LockedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Models\Student;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StudentInfoUpdatePendingState extends StudentInfoUpdateState
{
    /**
     * Handle the approval of the student info update.
     *
     * @throws LockedException If the status is not approved.
     * @throws AccessDeniedHttpException If the user does not have permission.
     * @throws UpdateResourceFailedException
     */
    public function handleApproval(): void
    {
        // Retrieve the currently authenticated student.
        $student = auth(AuthApiSection::Student->value)->user();

        // If no student is authenticated, throw an AccessDeniedHttpException.
        if (! $student) {
            throw new AccessDeniedHttpException();
        }

        // Check if the current status is among the approved statuses for class officers.
        if (! $this->isStatusApproved(StudentInfoUpdateStatus::ClassOfficerApproved)) {
            // If the status is not approved, throw a LockedException.
            throw new LockedException();
        }

        // Update the status of the student info update request.
        $this->handleChangeStatusRequestUpdate();

        // Create an approval user entry with the approval ID and model.
        $this->context->createApprovalUser($this->getApproveId(), $this->getApproveModel());

        // Transition to the next state, which is the class officer approved state.
        $this->context->transitionTo(new StudentInfoUpdateClassOfficerApprovedState($this->status));
    }

    /**
     * Get the name of the model to be approved.
     *
     * @return string The name of the model.
     */
    public function getApproveModel(): string
    {
        return Student::class;
    }

    /**
     * Get the ID of the entity to be approved.
     *
     * @return int The ID of the entity.
     */
    public function getApproveId(): int
    {
        return auth(AuthApiSection::Student->value)->id();
    }
}
