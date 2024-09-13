<?php

declare(strict_types=1);

namespace App\Services\StudentInfoRequest\States;

use App\Enums\AuthApiSection;
use App\Enums\StudentInfoUpdateStatus;
use App\Enums\UserRole;
use App\Exceptions\LockedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Factories\Student\UpdateStudentInfoDTOByStudentInfoUpdateFactory;
use App\Models\User;
use App\Services\Student\StudentInfo\StudentInfoService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StudentInfoUpdateTeacherApprovedState extends StudentInfoUpdateState
{
    /**
     * Handle the approval of the student info update.
     *
     * @throws LockedException If the status is not approved.
     * @throws AccessDeniedHttpException If the user does not have permission.
     * @throws BindingResolutionException If there is a binding resolution error.
     * @throws UpdateResourceFailedException
     */
    public function handleApproval(): void
    {
        // Check if the user has the admin permission.
        if (UserRole::Admin === auth()->user()->role || auth()->user()->is_super_admin) {
            throw new AccessDeniedHttpException();
        }

        // Check if the current status is among the approved statuses.
        if (! $this->isStatusApproved(StudentInfoUpdateStatus::OfficerApproved)) {
            throw new LockedException();
        }

        // Update the status of the student info update request.
        $this->handleChangeStatusRequestUpdate();

        // Create an approval user entry with the approval ID and model.
        $this->context->createApprovalUser($this->getApproveId(), $this->getApproveModel());

        // Transition to the next state, which is the class officer approved state.
        $this->context->transitionTo(new StudentInfoUpdateOfficerApprovedState($this->status));

        // Update student info by data request from student info update
        app()->make(StudentInfoService::class)
            ->update(UpdateStudentInfoDTOByStudentInfoUpdateFactory::make(
                studentInfoUpdate: $this->context->getStudentInfoUpdate()
            ));
    }

    /**
     * Get the name of the model to be approved.
     *
     * @return string The name of the model.
     */
    public function getApproveModel(): string
    {
        return User::class;
    }

    /**
     * Get the ID of the entity to be approved.
     *
     * @return int The ID of the entity.
     */
    public function getApproveId(): int
    {
        return auth(AuthApiSection::Admin->value)->id();
    }
}
