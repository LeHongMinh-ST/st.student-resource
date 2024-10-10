<?php

declare(strict_types=1);

namespace App\Factories\Warning;

use App\DTO\Warning\UpdateStudentWarningDTO;
use App\Http\Requests\Admin\StudentWarning\UpdateStudentWarningRequest;

class UpdateStudentWarningDTOFactory
{
    public static function make(UpdateStudentWarningRequest $request, int $id): UpdateStudentWarningDTO
    {
        $command = new UpdateStudentWarningDTO();
        $command->setId($id);
        $command->setName($request->get('name'));
        $command->setSemesterId($request->get('semester_id'));
        return $command;
    }
}
