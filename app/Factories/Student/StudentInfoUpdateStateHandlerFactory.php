<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\Enums\StudentInfoUpdateStatus;
use App\Models\StudentInfoUpdate;
use App\Services\StudentInfoRequest\States\StudentInfoUpdateClassOfficerApprovedState;
use App\Services\StudentInfoRequest\States\StudentInfoUpdateContext;
use App\Services\StudentInfoRequest\States\StudentInfoUpdateOfficerApprovedState;
use App\Services\StudentInfoRequest\States\StudentInfoUpdatePendingState;
use App\Services\StudentInfoRequest\States\StudentInfoUpdateRejectedState;
use App\Services\StudentInfoRequest\States\StudentInfoUpdateTeacherApprovedState;
use InvalidArgumentException;

class StudentInfoUpdateStateHandlerFactory
{
    /**
     * Create a new StudentInfoUpdate instance based on the provided data and status.
     *
     * This method creates a new StudentInfoUpdate object based on the provided data and status,
     * and handles approval or rejection logic accordingly.
     *
     * @param  StudentInfoUpdate  $studentInfoUpdate  The original StudentInfoUpdate object.
     * @param  StudentInfoUpdateStatus  $status  The status of the update.
     * @param  string|null  $rejectionNote  The reject note.
     * @return StudentInfoUpdateContext Returns the updated StudentInfoUpdateContext object.
     */
    public static function make(
        StudentInfoUpdate $studentInfoUpdate,
        StudentInfoUpdateStatus $status,
        ?string $rejectionNote = null
    ): StudentInfoUpdateContext {
        // Determine the appropriate state class based on the status
        $stateClass = match ($studentInfoUpdate->status) {
            StudentInfoUpdateStatus::Pending => new StudentInfoUpdatePendingState($status),
            StudentInfoUpdateStatus::ClassOfficerApproved => new StudentInfoUpdateClassOfficerApprovedState($status),
            StudentInfoUpdateStatus::TeacherApproved => new StudentInfoUpdateTeacherApprovedState($status),
            StudentInfoUpdateStatus::OfficerApproved => new StudentInfoUpdateOfficerApprovedState($status),
            StudentInfoUpdateStatus::Rejected => new StudentInfoUpdateRejectedState($status),
            default => throw new InvalidArgumentException('Invalid state'),
        };

        // Create a context with the determined state class and original StudentInfoUpdate object
        // Return the updated StudentInfoUpdate object
        return new StudentInfoUpdateContext($stateClass, $studentInfoUpdate, $rejectionNote);
    }
}
