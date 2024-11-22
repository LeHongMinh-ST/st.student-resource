<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\ListStudentSurveyDTO;
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

        if ($request->has('q')) {
            $command->setQ($request->get('q'));
        }
        if ($request->has('class_id')) {
            $command->setClassId((int) $request->get('class_id'));
        }
        if ($request->has('graduation_id')) {
            $command->setGraduationId((int) $request->get('graduation_id'));
        }

        if ($request->has('status_survey') && in_array($request->get('status_survey'), ['response', 'unresponse'])) {
            if ('response' === $request->get('status_survey')) {
                $command->setIsResponse(1);
            } else {
                $command->setIsResponse(0);
            }
        }

        return $command;
    }
}
