<?php

declare(strict_types=1);

namespace App\Factories\Quit;

use App\DTO\Quit\UpdateStudentQuitDTO;
use App\Http\Requests\Admin\StudentWarning\UpdateStudentWarningRequest;

class UpdateStudentQuitDTOFactory
{
    public static function make(UpdateStudentWarningRequest $request, int $id): UpdateStudentQuitDTO
    {
        $command = new UpdateStudentQuitDTO();
        $command->setId($id);
        $command->setName($request->get('name'));
        $command->setSemesterId($request->get('semester_id'));
        return $command;
    }
}
