<?php

declare(strict_types=1);

namespace App\Services\StudentInfoRequest\States;

use App\Enums\StudentInfoUpdateStatus;
use App\Exceptions\UpdateResourceFailedException;
use Exception;

/**
 * The base State class declares methods that all Concrete States should
 * implement and also provides a backreference to the Context object, associated
 * with the State. This backreference can be used by States to transition the
 * Context to another State.
 */
abstract class StudentInfoUpdateState
{
    protected StudentInfoUpdateContext $context;

    public function __construct(
        protected StudentInfoUpdateStatus $status
    ) {
    }

    /**
     * Handle the approval of the student info update.
     * This method must be implemented by subclasses.
     */
    abstract public function handleApproval(): void;

    /**
     * Get the name of the model to be approved.
     * This method must be implemented by subclasses.
     *
     * @return string The name of the model.
     */
    abstract public function getApproveModel(): string;

    /**
     * Get the ID of the entity to be approved.
     * This method must be implemented by subclasses.
     *
     * @return int The ID of the entity.
     */
    abstract public function getApproveId(): int;

    /**
     * Handle the rejection of the student info update.
     * Changes the status to rejected and transitions to the rejected state.
     *
     * @throws UpdateResourceFailedException
     */
    public function handleRejection(): void
    {
        // Update the status of the student info update request.
        $this->handleChangeStatusRequestUpdate();

        // Create an approval user entry with the approval ID and model.
        $this->context->createApprovalUser($this->getApproveId(), $this->getApproveModel());

        // Transition to the rejected state.
        $this->context->transitionTo(new StudentInfoUpdateRejectedState($this->status));
    }

    /**
     * Set the context for the state.
     *
     * @param  StudentInfoUpdateContext  $context  The context object.
     */
    public function setContext(StudentInfoUpdateContext $context): void
    {
        $this->context = $context;
    }

    /**
     * Check if the current status is among the approved statuses.
     *
     * @param  StudentInfoUpdateStatus  ...$status  The list of approved statuses.
     * @return bool True if the current status is approved, false otherwise.
     */
    public function isStatusApproved(StudentInfoUpdateStatus ...$status): bool
    {
        return in_array($this->status, $status);
    }

    /**
     * Update the status of the student info update request and save it.
     *
     * @throws UpdateResourceFailedException
     */
    public function handleChangeStatusRequestUpdate(): void
    {
        try {
            // Get the current student info update object from the context.
            $studentInfoUpdate = $this->context->getStudentInfoUpdate();

            // Update the status of the student info update object.
            $studentInfoUpdate->status = $this->status;

            // Save the updated student info update object to the database.
            $studentInfoUpdate->save();
        } catch (Exception) {
            throw new UpdateResourceFailedException();
        }
    }
}
