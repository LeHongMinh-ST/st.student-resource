<?php

declare(strict_types=1);

namespace App\Services\StudentInfoRequest;

use App\DTO\Student\ChangeStatusRequestUpdateStudentDTO;
use App\DTO\Student\CreateApproveStudentUpdateDTO;
use App\Enums\StudentInfoUpdateStatus;
use App\Exceptions\UpdateResourceFailedException;
use App\Factories\Student\StudentInfoUpdateStateHandlerFactory;
use App\Models\ApproveStudentUpdate;
use App\Models\StudentInfoUpdate;

class ApproveStudentUpdateService
{
    public function create(CreateApproveStudentUpdateDTO $approveStudentUpdateDTO): StudentInfoUpdate
    {
        return ApproveStudentUpdate::create($approveStudentUpdateDTO->toArray());
    }

    public function update(): void
    {

    }

    /**
     * @throws UpdateResourceFailedException
     */
    public function updateStatus(ChangeStatusRequestUpdateStudentDTO $command): StudentInfoUpdate
    {

        $request = StudentInfoUpdate::find($command->getStudentInfoUpdateId());
        $requestUpdateStateContext = StudentInfoUpdateStateHandlerFactory::make($request, $command->getStatus());

        if (StudentInfoUpdateStatus::Rejected === $command->getStatus()) {
            $requestUpdateStateContext->handleRejection();
        } else {
            $requestUpdateStateContext->handleApproval();
        }

        return $requestUpdateStateContext->getStudentInfoUpdate();
    }
}
