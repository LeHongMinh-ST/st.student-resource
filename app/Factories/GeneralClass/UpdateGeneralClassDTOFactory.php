<?php

declare(strict_types=1);

namespace App\Factories\GeneralClass;

use App\DTO\GeneralClass\UpdateGeneralClassDTO;
use App\Http\Requests\Admin\GeneralClass\UpdateGeneralClassRequest;
use App\Models\GeneralClass;

class UpdateGeneralClassDTOFactory
{
    public static function make(UpdateGeneralClassRequest $request, GeneralClass $generalClass): UpdateGeneralClassDTO
    {
        // Create a new UpdateGeneralClassDTO object
        $command = new UpdateGeneralClassDTO();

        // Set properties of the GeneralClassCreateCommand object based on the provided faculty and email (or generate a default email)
        $command->setId($generalClass->id);
        $command->setName($request->get('name'));
        $command->setCode($request->get('code'));
        $command->setStatus($request->get('status'));
        $command->setMajorId($request->get('major_id'));
        $command->setType($request->get('type'));
        $command->setTeacherId((int)$request->get('teacher_id'));
        $command->setSubTeacherId((int)$request->get('sub_teacher_id'));

        if ($request->has('officer.student_president.id')) {
            $command->setStudentPresidentId((int) $request->input('officer.student_president.id'));
        }

        if ($request->has('officer.student_secretary.id')) {
            $command->setStudentSecretaryId((int) $request->input('officer.student_secretary.id'));
        }

        return $command;
    }
}
