<?php

declare(strict_types=1);

namespace App\Factories\RequestUpdateStudent;

use App\DTO\Student\UpdateRequestUpdateStudentDTO;
use App\Enums\AuthApiSection;
use App\Http\Requests\Student\RequestUpdateInfo\UpdateRequestUpdateStudentRequest;
use App\Models\StudentInfoUpdate;

class UpdateRequestUpdateStudentDTOFactory
{
    public static function make(UpdateRequestUpdateStudentRequest $request, StudentInfoUpdate $infoUpdate): UpdateRequestUpdateStudentDTO
    {
        $command = new UpdateRequestUpdateStudentDTO();
        $command->setId($infoUpdate->id);
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
            $families[] = UpdateRequestUpdateFamilyStudentDTOFactory::make($family, $command->getId());
        }
        $command->setFamily($families);

        return $command;
    }
}
