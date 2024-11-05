<?php

declare(strict_types=1);

namespace App\Factories\SurveyPeriod;

use App\DTO\SurveyPeriod\CreateSurveyPeriodDTO;
use App\Enums\Status;
use App\Enums\SurveyPeriodType;
use App\Http\Requests\Admin\SurveyPeriod\StoreSurveyPeriodRequest;
use Carbon\Carbon;

class CreateSurveyPeriodDTOFactory
{
    public static function make(StoreSurveyPeriodRequest $request): CreateSurveyPeriodDTO
    {
        $dto = new CreateSurveyPeriodDTO();
        $dto->setTitle($request->get('title'));
        $dto->setStartDate(Carbon::createFromFormat('Y-m-d H:i', $request->get('start_date')));
        $dto->setEndDate(Carbon::createFromFormat('Y-m-d H:i', $request->get('end_date')));
        $dto->setStatus($request->has('status') ? Status::from($request->get('status')) : Status::Enable);
        $dto->setDescription($request->get('description'));
        $dto->setYear($request->get('year'));
        $dto->setType(SurveyPeriodType::EmploymentSurvey);
        $dto->setFacultyId((int) auth()->user()->faculty_id);
        $dto->setGraduationCeremonyIds($request->get('graduation_ceremony_ids'));
        $dto->setCreatedBy((int) auth()->user()->id);
        $dto->setUpdatedBy((int) auth()->user()->id);

        return $dto;
    }
}
