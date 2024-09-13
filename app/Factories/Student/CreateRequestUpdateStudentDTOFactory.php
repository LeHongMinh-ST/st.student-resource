<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\CreateRequestUpdateStudentDTO;
use App\Enums\AuthApiSection;
use App\Http\Requests\Student\RequestUpdateInfo\CreateRequestUpdateStudentRequest;

class CreateRequestUpdateStudentDTOFactory
{
    public static function make(CreateRequestUpdateStudentRequest $request): CreateRequestUpdateStudentDTO
    {
        $command = new CreateRequestUpdateStudentDTO();
        $command->setStudentId(auth(AuthApiSection::Student->value)->id());
        $command->setPersonEmail($request->get('person_email'));
        $command->setNote($request->get('note'));
        $command->setDob($request->get('dob'));
        $command->setPob($request->get('pob'));
        $command->setPhone($request->get('phone'));
        $command->setGender($request->get('gender'));
        $command->setEthnic($request->get('ethnic'));
        $command->setNationality($request->get('nationality'));
        $command->setReligion($request->get('religion'));
        $command->setCountryside($request->get('countryside'));
        $command->setAddress($request->get('address'));
        $families = [];
        foreach ($request->get('families') as $family) {
            $families[] = CreateRequestUpdateFamilyStudentDTOFactory::make($family);
        }
        $command->setFamily($families);

        return $command;
    }
}
