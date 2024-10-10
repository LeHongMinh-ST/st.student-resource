<?php

declare(strict_types=1);

namespace App\Factories\Graduation;

use App\DTO\Graduation\CreateGraduationDTO;
use App\Http\Requests\Admin\Graduation\StoreGraduationRequest;

class CreateGraduationDTOFactory
{
    public static function make(StoreGraduationRequest $request): CreateGraduationDTO
    {
        $command = new CreateGraduationDTO();
        $command->setCertification($request->get('certification'));
        $command->setSchoolYear($request->get('school_year'));
        $command->setName($request->get('name'));
        $command->setCertificationDate($request->get('certification_date'));
        return $command;
    }

}
