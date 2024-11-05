<?php

declare(strict_types=1);

namespace App\Factories\SurveyPeriod;

use App\DTO\SurveyPeriod\UpdateSurveyPeriodDTO;
use App\Enums\Status;
use App\Http\Requests\Admin\SurveyPeriod\UpdateSurveyPeriodRequest;
use App\Models\SurveyPeriod;
use Illuminate\Support\Carbon;

class UpdateSurveyPeriodDTOFactory
{
    public static function make(UpdateSurveyPeriodRequest $request, SurveyPeriod $surveyPeriod): UpdateSurveyPeriodDTO
    {
        $command = new UpdateSurveyPeriodDTO();

        $command->setId($surveyPeriod->id);
        $command->setTitle($request->get('title'));
        $command->setDescription($request->get('description'));
        $command->setStatus($request->get('status') ? Status::from($request->get('status')) : null);
        $command->setStartDate($request->get('start_date') ? Carbon::createFromFormat('Y-m-d H:i', $request->get('start_date')) : null);
        $command->setEndDate($request->get('end_date') ? Carbon::createFromFormat('Y-m-d H:i', $request->get('end_date')) : null);
        $command->setGraduationCeremonyIds($request->get('graduation_ceremony_ids'));
        $command->setUpdatedBy((int) auth()->user()->id);

        return $command;
    }
}
