<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\UpdateStudentInfoDTO;
use App\Models\StudentInfoUpdate;

class UpdateStudentInfoDTOByStudentInfoUpdateFactory
{
    /**
     * Create a UpdateStudentCommand instance from a StudentInfoUpdate.
     *
     * @param StudentInfoUpdate $studentInfoUpdate
     * @return UpdateStudentInfoDTO
     */
    public static function make(StudentInfoUpdate $studentInfoUpdate): UpdateStudentInfoDTO
    {
        $infoCommand = new UpdateStudentInfoDTO();
        $infoCommand->setId($studentInfoUpdate->id);
        $infoCommand->setPersonEmail($studentInfoUpdate->person_email);
        $infoCommand->setGender($studentInfoUpdate->gender);
        $infoCommand->setPermanentResidence($studentInfoUpdate->permanent_residence);
        $infoCommand->setDob($studentInfoUpdate->dob);
        $infoCommand->setPob($studentInfoUpdate->pob);
        $infoCommand->setCountryside($studentInfoUpdate->countryside);
        $infoCommand->setAddress($studentInfoUpdate->address);
        $infoCommand->setTrainingType($studentInfoUpdate->training_type);
        $infoCommand->setPhone($studentInfoUpdate->phone);
        $infoCommand->setNationality($studentInfoUpdate->nationality);
        $infoCommand->setCitizenIdentification($studentInfoUpdate->citizen_identification);
        $infoCommand->setEthnic($studentInfoUpdate->ethnic);
        $infoCommand->setReligion($studentInfoUpdate->religion);

        return $infoCommand;
    }
}
