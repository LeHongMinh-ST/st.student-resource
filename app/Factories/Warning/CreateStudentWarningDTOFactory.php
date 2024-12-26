<?php

declare(strict_types=1);

namespace App\Factories\Warning;

use App\DTO\Warning\CreateStudentWarningDTO;
use App\Http\Requests\Admin\StudentWarning\StoreStudentWarningRequest;

class CreateStudentWarningDTOFactory
{
    public static function make(StoreStudentWarningRequest $request): CreateStudentWarningDTO
    {
        $command = new CreateStudentWarningDTO();
        $command->setName($request->get('name'));
        $command->setSemesterId((int)$request->get('semester_id'));
        return $command;
    }
}
