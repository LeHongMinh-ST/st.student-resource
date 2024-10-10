<?php

declare(strict_types=1);

namespace App\Factories\Quit;

use App\DTO\Quit\CreateStudentQuitDTO;
use App\Http\Requests\Admin\StudentQuit\StoreStudentQuitRequest;

class CreateStudentQuitDTOFactory
{
    public static function make(StoreStudentQuitRequest $request): CreateStudentQuitDTO
    {
        $command = new CreateStudentQuitDTO();
        $command->setName($request->get('name'));
        $command->setSemesterId($request->get('semester_id'));
        return $command;
    }
}
