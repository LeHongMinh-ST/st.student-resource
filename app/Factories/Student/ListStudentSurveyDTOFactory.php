<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\ListStudentSurveyDTO;
use App\Enums\StudentSurveyStatus;
use App\Http\Requests\Admin\Student\ListStudentRequest;
use App\Supports\MakeDataHelper;

class ListStudentSurveyDTOFactory
{
    public static function make(ListStudentRequest $request, mixed $id): ListStudentSurveyDTO
    {
        // Create a new ListStudentDTO object
        $command = new ListStudentSurveyDTO();

        // Set command properties based on the request parameters, if they exist
        $command = MakeDataHelper::makeListData($request, $command);

        $command->setSurveyPeriodId((int) $id);
        if ($request->has('status_survey')) {
            $command->setStatus(StudentSurveyStatus::from($request->get('status_survey')));
        }

        if ($request->has('q')) {
            $command->setQ($request->get('q'));
        }
        if ($request->has('class_id')) {
            $command->setClassId((int) $request->get('class_id'));
        }
        if ($request->has('graduation_id')) {
            $command->setGraduationId((int) $request->get('graduation_id'));
        }

        return $command;
    }
}
