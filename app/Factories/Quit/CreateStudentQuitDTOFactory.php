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
        $command->setCertification($request->get('certification'));
        $command->setYear($request->get('year'));
        $command->setName($request->get('name'));
        $command->setCertificationDate($request->get('certification_date'));
        return $command;
    }
}
