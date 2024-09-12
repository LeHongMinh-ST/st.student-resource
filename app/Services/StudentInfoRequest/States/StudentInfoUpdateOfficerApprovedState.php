<?php

declare(strict_types=1);

namespace App\Services\StudentInfoRequest\States;

use App\Exceptions\LockedException;

class StudentInfoUpdateOfficerApprovedState extends StudentInfoUpdateTeacherApprovedState
{
    /**
     * Handle the approval of the student info update.
     *
     * @throws LockedException This method always throws a LockedException to indicate that approval is not allowed in the current state.
     */
    public function handleApproval(): void
    {
        throw new LockedException();
    }

    /**
     * Handle the rejection of the student info update.
     *
     * @throws LockedException This method always throws a LockedException to indicate that rejection is not allowed in the current state.
     */
    public function handleRejection(): void
    {
        throw new LockedException();
    }
}
