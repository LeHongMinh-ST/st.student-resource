<?php

declare(strict_types=1);

namespace App\Factories\TrainingIndustry;

use App\DTO\TrainingIndustry\ListTrainingIndustryDTO;
use App\Enums\Status;
use App\Http\Requests\Student\TrainingIndustry\ExternalListTrainingIndustryRequest;
use App\Supports\MakeDataHelper;

class ExternalListTrainingIndustryDTOFactory
{
    public static function make(ExternalListTrainingIndustryRequest $request): ListTrainingIndustryDTO
    {
        $command = new ListTrainingIndustryDTO();

        if ($request->has('q')) {
            $command->setQ($request->q);
        }

        if ($request->has('faculty_id')) {
            $command->setFacultyId($request->faculty_id);
        }

        if ($request->has('status')) {
            $command->setStatus(Status::from($request->status));
        }

        // Set command properties based on the request parameters, if they exist
        return MakeDataHelper::makeListData($request, $command);
    }
}
