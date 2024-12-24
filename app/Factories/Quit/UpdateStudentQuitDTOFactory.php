<?php

declare(strict_types=1);

namespace App\Factories\Quit;

use App\DTO\Quit\UpdateStudentQuitDTO;
use App\Http\Requests\Admin\StudentQuit\UpdateStudentQuitRequest;

class UpdateStudentQuitDTOFactory
{
    public static function make(UpdateStudentQuitRequest $request, int $id): UpdateStudentQuitDTO
    {
        $command = new UpdateStudentQuitDTO();
        $command->setId($id);
        $command->setCertification($request->get('certification'));
        $command->setYear((int)$request->get('year'));
        $command->setName($request->get('name'));
        $command->setCertificationDate($request->get('certification_date'));
        return $command;
    }
}
