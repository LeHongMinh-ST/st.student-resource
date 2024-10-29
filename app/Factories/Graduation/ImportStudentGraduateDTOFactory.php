<?php

declare(strict_types=1);

namespace App\Factories\Graduation;

use App\DTO\Graduation\ImportStudentGraduateDTO;
use App\Http\Requests\Admin\Graduation\ImportStudentGraduateRequest;

class ImportStudentGraduateDTOFactory
{
    public static function make(ImportStudentGraduateRequest $importStudentGraduateRequest): ImportStudentGraduateDTO
    {
        $command = new ImportStudentGraduateDTO();
        $command->setGraduationCeremoniesId((int) $importStudentGraduateRequest->get('graduation_ceremony_id'));
        $command->setFile($importStudentGraduateRequest->file('file'));
        return $command;
    }
}
