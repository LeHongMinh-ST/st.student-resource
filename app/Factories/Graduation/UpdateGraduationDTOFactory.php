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
        if ($request->has('name')) {
            $command->setName($request->get('name'));
        }
        if ($request->has('year')) {
            $command->setYear($request->get('year'));
        }
        if ($request->has('certification')) {
            $command->setCertification($request->get('certification'));
        }
        if ($request->has('certification_date')) {
            $command->setCertificationDate($request->get('certification_date'));
        }
        return $command;
    }
}
