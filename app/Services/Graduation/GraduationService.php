<?php

declare(strict_types=1);

namespace App\Services\Graduation;

use App\DTO\Graduation\ImportStudentGraduateDTO;
use App\Enums\AuthApiSection;
use App\Enums\ExcelImportType;
use App\Models\ExcelImportFile;
use App\Supports\Constants;
use App\Supports\ExcelFileHelper;

class GraduationService
{
    public function importStudentGraduate(ImportStudentGraduateDTO $importStudentGraduateDTO): void
    {
        $data = ExcelFileHelper::chunkFileToCsv($importStudentGraduateDTO->getFile(), ExcelImportType::Graduate);

        $excelImportFile = ExcelImportFile::create([
            'name' => $importStudentGraduateDTO->getFile()->getClientOriginalName(),
            'type' => ExcelImportType::Graduate,
            'total_record' => $data['total_row_data'] - Constants::getNumberRowNotRecord(),
            'process_record' => 0,
            'faculty_id' => auth(AuthApiSection::Admin->value)->user()?->faculty_id,
            'user_id' => auth(AuthApiSection::Admin->value)->id(),
            'type_id' => $importStudentGraduateDTO->getGraduationCeremoniesId(),
        ]);
    }
}
