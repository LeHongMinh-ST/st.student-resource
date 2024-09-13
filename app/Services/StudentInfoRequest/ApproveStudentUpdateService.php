<?php

declare(strict_types=1);

namespace App\Services\StudentInfoRequest;

use App\DTO\Student\ChangeStatusRequestUpdateStudentDTO;
use App\Enums\StudentInfoUpdateStatus;
use App\Factories\Student\StudentInfoUpdateStateHandlerFactory;
use App\Models\StudentInfoUpdate;

class ApproveStudentUpdateService
{
    public function update(ChangeStatusRequestUpdateStudentDTO $command): StudentInfoUpdate
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
