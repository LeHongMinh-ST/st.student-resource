<?php

declare(strict_types=1);

namespace App\Factories\GeneralClass;

use App\DTO\GeneralClass\CreateGeneralClassDTO;
use App\Enums\ClassType;
use App\Enums\Status;
use App\Http\Requests\Admin\GeneralClass\StoreGeneralClassRequest;

class CreateGeneralClassDTOFactory
{
    public static function make(StoreGeneralClassRequest $request): CreateGeneralClassDTO
    {
        // Create a new GeneralClassCreateCommand object
        $command = new CreateGeneralClassDTO();

        // Set properties of the GeneralClassCreateCommand object based on the provided faculty and email (or generate a default email)
        $command->setName($request->get('name'));
        $command->setCode($request->get('code'));
        $command->setStatus($request->get('status', Status::Enable->value));
        $command->setFacultyId(auth()->user()->faculty_id);
        $command->setMajorId($request->get('major_id'));
        $command->setTeacherId($request->get('teacher_id'));
        $command->setAdmissionYearId((int)$request->get('admission_year_id'));
        $command->setType($request->get('type', ClassType::Basic->value));


        return $command;
    }
}
