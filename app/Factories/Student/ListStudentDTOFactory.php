<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\ListStudentDTO;
use App\Enums\StudentStatus;
use App\Http\Requests\Admin\Student\ListStudentRequest;
use App\Supports\MakeDataHelper;

class ListStudentDTOFactory
{
    public static function make(ListStudentRequest $request): ListStudentDTO
    {
        // Create a new ListStudentDTO object
        $command = new ListStudentDTO();

        // Set command properties based on the request parameters, if they exist
        $command = MakeDataHelper::makeListData($request, $command);

        if ($request->has('admission_year_id')) {
            $command->setAdmissionYearId((int) $request->get('admission_year_id'));
        }

        if ($request->has('status')) {
            $command->setStatus(StudentStatus::from($request->get('status')));
        }

        if ($request->has('q')) {
            $command->setQ($request->get('q'));
        }

        return $command;
    }
}
