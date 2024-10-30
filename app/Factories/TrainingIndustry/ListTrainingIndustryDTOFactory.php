<?php

declare(strict_types=1);

namespace App\Factories\TrainingIndustry;

use App\DTO\TrainingIndustry\ListTrainingIndustryDTO;
use App\Enums\Status;
use App\Http\Requests\Admin\TrainingIndustry\ListTrainingIndustryRequest;
use App\Supports\MakeDataHelper;

class ListTrainingIndustryDTOFactory
{
    public static function make(ListTrainingIndustryRequest $request): ListTrainingIndustryDTO
    {
        $command = new ListTrainingIndustryDTO();

        if ($request->has('q')) {
            $command->setQ($request->q);
        }

        if ($request->has('faculty_id')) {
            $command->setFacultyId((int) auth()->user()->faculty_id);
        }

        if ($request->has('status')) {
            $command->setStatus(Status::from($request->status));
        }

        // Set command properties based on the request parameters, if they exist
        return MakeDataHelper::makeListData($request, $command);
    }
}
