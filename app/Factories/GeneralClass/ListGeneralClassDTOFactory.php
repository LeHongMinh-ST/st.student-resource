<?php

declare(strict_types=1);

namespace App\Factories\GeneralClass;

use App\DTO\GeneralClass\ListGeneralClassDTO;
use App\Http\Requests\Admin\GeneralClass\ListGeneralClassRequest;
use App\Supports\MakeDataHelper;

class ListGeneralClassDTOFactory
{
    public static function make(ListGeneralClassRequest $request): ListGeneralClassDTO
    {
        // Create a new ListFacultyCommand object
        $command = new ListGeneralClassDTO();
        $command->setQ($request->q);
        $command->setStatus($request->status);
        $command->setFacultyId(auth()->user()->faculty_id);
        $command->setType($request->type);
        $command->setTypeClass($request->type_class);
        $command->setTeacherId($request->teacher_id ? (int) $request->teacher_id : null);
        $command->setSubTeacherId($request->sub_teacher_id ? (int) $request->sub_teacher_id : null);
        if ($request->has('admission_year_id')) {
            $command->setAdmissionYearId((int)$request->admission_year_id);
        }
        if ($request->has('training_industry_id')) {
            $command->setTrainingIndustryId((int)$request->training_industry_id);
        }

        // Set command properties based on the request parameters, if they exist
        return MakeDataHelper::makeListData($request, $command);
    }
}
