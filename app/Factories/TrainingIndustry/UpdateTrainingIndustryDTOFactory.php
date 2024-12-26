<?php

declare(strict_types=1);

namespace App\Factories\TrainingIndustry;

use App\DTO\TrainingIndustry\UpdateTrainingIndustryDTO;
use App\Enums\Status;
use App\Http\Requests\Admin\TrainingIndustry\UpdateTrainingIndustryRequest;
use App\Models\TrainingIndustry;

class UpdateTrainingIndustryDTOFactory
{
    public static function make(UpdateTrainingIndustryRequest $request, TrainingIndustry $trainingIndustry): UpdateTrainingIndustryDTO
    {
        $command = new UpdateTrainingIndustryDTO();


        $command->setId($trainingIndustry->id);
        $command->setName($request->get('name') ?? $trainingIndustry->name);
        $command->setDescription($request->get('description') ?? $trainingIndustry->description);
        $command->setStatus($request->has('status') ? Status::from($request->get('status')) : $trainingIndustry->status);

        return $command;
    }
}
