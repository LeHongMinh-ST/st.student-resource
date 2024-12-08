<?php

declare(strict_types=1);

namespace App\Factories\Graduation;

use App\DTO\Graduation\ListGraduationDTO;
use App\Http\Requests\Admin\Graduation\ListGraduationRequest;
use App\Supports\MakeDataHelper;

class ListGraduationDTOFactory
{
    public static function make(ListGraduationRequest $request): ListGraduationDTO
    {
        $command = new ListGraduationDTO();
        $command = MakeDataHelper::makeListData($request, $command);
        $command->setCertification($request->get('certification'));
        if ($request->has('year')) {
            $command->setYear((int) $request->get('year'));
        }
        if ($request->has('with_id_survey_period')) {
            $command->setWithIdSurveyPeriod((int) $request->get('with_id_survey_period'));
        }
        if ($request->has('is_graduation_doesnt_have_survey_period')) {
            $command->setIsGraduationDoesntHaveSurveyPeriod((bool) $request->get('is_graduation_doesnt_have_survey_period'));
        }
        return $command;
    }
}
