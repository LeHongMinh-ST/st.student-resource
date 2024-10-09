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
        $command->setGraduationCeremoniesId($importStudentGraduateRequest->get('graduation_ceremonies_id'));
        $command->setFile($importStudentGraduateRequest->file('file'));
        return $command;
    }
}
