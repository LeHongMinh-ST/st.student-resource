<?php

declare(strict_types=1);

namespace App\Factories\RequestUpdateStudent;

use App\DTO\Student\ListRequestUpdateStudentDTO;
use App\Enums\StudentInfoUpdateStatus;
use App\Http\Requests\Student\RequestUpdateInfo\ListMyRequestUpdateStudentRequest;
use App\Http\Requests\Student\RequestUpdateInfo\ListRequestUpdateStudentRequest;
use App\Supports\MakeDataHelper;

class ListRequestUpdateStudentDTOFactory
{
    public static function make(ListRequestUpdateStudentRequest|ListMyRequestUpdateStudentRequest $request, ?int $studentId = null, ?int $classId = null): ListRequestUpdateStudentDTO
    {
        // Create a new ListStudentDTO object
        $command = new ListRequestUpdateStudentDTO();

        // Set command properties based on the request parameters, if they exist
        $command = MakeDataHelper::makeListData($request, $command);

        if ($request->has('status')) {
            $command->setStatus(StudentInfoUpdateStatus::from($request->get('status')));
        }

        if ($request->has('class_id')) {
            $command->setClassId($request->get('class_id'));
        }

        if ($studentId) {
            $command->setStudentId($studentId);
        }

        if ($classId) {
            $command->setClassId($classId);
        }

        return $command;
    }
}
