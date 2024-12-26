<?php

declare(strict_types=1);

namespace App\Factories\ExcelImportFile;

use App\DTO\ExcelImportFile\ImportStudentFileTypeDTO;
use App\Enums\AuthApiSection;
use App\Enums\ExcelImportType;
use App\Http\Requests\Admin\ExcelImportFile\ImportStudentFileRequest;

class ImportStudentFileDTOFactory
{
    public static function make(ImportStudentFileRequest $request): ImportStudentFileTypeDTO
    {
        $command = new ImportStudentFileTypeDTO();
        $command->setFile($request->file('file'));
        $command->setType(ExcelImportType::from($request->get('type')));
        $command->setTypeId((int) $request->get('entity_id'));
        $command->setFacultyId((int) auth(AuthApiSection::Admin->value)->user()?->faculty_id);

        return $command;
    }
}
