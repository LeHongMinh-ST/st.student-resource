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
        $command->setYear($request->get('year'));
        if ($request->has('is_graduation_doesnt_have_survey_period')) {
            $command->setIsGraduationDoesntHaveSurveyPeriod((bool) $request->get('is_graduation_doesnt_have_survey_period'));
        }
        return $command;
    }
}
