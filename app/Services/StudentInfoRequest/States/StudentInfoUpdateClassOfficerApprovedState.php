<?php

declare(strict_types=1);

namespace App\Services\StudentInfoRequest\States;

use App\Enums\StudentInfoUpdateStatus;
use App\Enums\UserRole;
use App\Exceptions\LockedException;
use App\Exceptions\UpdateResourceFailedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StudentInfoUpdateClassOfficerApprovedState extends StudentInfoUpdateTeacherApprovedState
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
        // Check if the user has the 'system.teacher' permission.
        if (UserRole::Teacher !== auth()->user()->role) {
            // Throw an exception if the user does not have permission.
            throw new AccessDeniedHttpException();
        }

        // Check if the current status is among the approved statuses.
        if (! $this->isStatusApproved(StudentInfoUpdateStatus::TeacherApproved)) {
            // Throw an exception if the status is not approved.
            throw new LockedException();
        }

        // Update the status of the student info update request.
        $this->handleChangeStatusRequestUpdate();

        // Create an approval user entry with the approval ID and model.
        $this->context->createApprovalUser($this->getApproveId(), $this->getApproveModel());

        // Transition to the next state, which is the class officer approved state.
        $this->context->transitionTo(new StudentInfoUpdateClassOfficerApprovedState($this->status));
    }
}
