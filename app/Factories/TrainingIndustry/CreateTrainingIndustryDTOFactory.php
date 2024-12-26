<?php

declare(strict_types=1);

namespace App\Factories\TrainingIndustry;

use App\DTO\TrainingIndustry\CreateTrainingIndustryDTO;
use App\Enums\Status;
use App\Http\Requests\Admin\TrainingIndustry\StoreTrainingIndustryRequest;

class CreateTrainingIndustryDTOFactory
{
    public static function make(StoreTrainingIndustryRequest $request): CreateTrainingIndustryDTO
    {
        $dto = new CreateTrainingIndustryDTO();
        $dto->setName($request->get('name'));
        $dto->setCode($request->get('code'));
        $dto->setDescription($request->get('description'));
        $dto->setStatus($request->has('status') ? Status::from($request->get('status')) : Status::Enable);
        $dto->setFacultyId((int) auth()->user()->faculty_id);

        return $dto;
    }
}
