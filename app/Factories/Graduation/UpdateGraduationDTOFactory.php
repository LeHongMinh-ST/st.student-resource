<?php

declare(strict_types=1);

namespace App\Factories\Graduation;

use App\DTO\Graduation\UpdateGraduationDTO;
use App\Http\Requests\Admin\Graduation\UpdateGraduationRequest;

class UpdateGraduationDTOFactory
{
    public static function make(UpdateGraduationRequest $request, int $id): UpdateGraduationDTO
    {
        $command = new UpdateGraduationDTO();
        $command->setId($id);
        $command->setCertification($request->get('certification'));
        $command->setSchoolYear($request->get('school_year'));
        $command->setName($request->get('name'));
        $command->setCertificationDate($request->get('certification_date'));
        return $command;
    }
}
