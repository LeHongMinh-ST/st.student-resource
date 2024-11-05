<?php

declare(strict_types=1);

namespace App\Factories\SurveyPeriod;

use App\DTO\SurveyPeriod\ListSurveyPeriodDTO;
use App\Enums\Status;
use App\Http\Requests\Admin\SurveyPeriod\ListSurveyPeriodRequest;
use App\Supports\MakeDataHelper;
use Carbon\Carbon;

class ListSurveyPeriodDTOFactory
{
    public static function make(ListSurveyPeriodRequest $request): ListSurveyPeriodDTO
    {
        $command = new ListSurveyPeriodDTO();

        if ($request->has('q')) {
            $command->setQ($request->q);
        }

        if ($request->has('faculty_id')) {
            $command->setFacultyId((int) auth()->user()->faculty_id);
        }

        if ($request->has('status')) {
            $command->setStatus(Status::from($request->status));
        }

        if ($request->has('year')) {
            $command->setYear($request->year);
        }

        if ($request->has('start_date')) {
            $command->setStartDate(
                Carbon::createFromFormat('Y-m-d', $request->start_date)->format('Y-m-d') . ' 00:00:00'
            );
        }

        if ($request->has('end_date')) {
            $command->setEndDate(
                Carbon::createFromFormat('Y-m-d', $request->end_date)->format('Y-m-d') . ' 23:59:59'
            );
        }

        // Set command properties based on the request parameters, if they exist
        return MakeDataHelper::makeListData($request, $command);
    }
}
