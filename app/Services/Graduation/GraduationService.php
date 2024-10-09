<?php

declare(strict_types=1);

namespace App\Services\Graduation;

use App\DTO\Graduation\ImportStudentGraduateDTO;
use App\Enums\AuthApiSection;
use App\Enums\ExcelImportType;
use App\Exceptions\CreateResourceFailedException;
use App\Jobs\CreateStudentGraduateByFileCsvJob;
use App\Models\ExcelImportFile;
use App\Supports\Constants;
use App\Supports\ExcelFileHelper;
use Exception;
use Illuminate\Support\Facades\Log;

class GraduationService
{
    public function getList(): void
    {

    }

    public function create(): void
    {

    }

    public function update(): void
    {

    }

    public function delete(): void
    {

    }

    /**
     * @throws CreateResourceFailedException
     */
    public function importStudentGraduate(ImportStudentGraduateDTO $importStudentGraduateDTO)
    {
        try {
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

            foreach ($data['file_names'] as $fileName) {
                // Create a new job to import student data
                CreateStudentGraduateByFileCsvJob::dispatch(
                    fileName: $fileName,
                    excelImportFileId: $excelImportFile->id,
                    faculty: auth()->user()->faculty,
                    userId: auth(AuthApiSection::Admin->value)->id(),
                    graduationCeremonyId: $importStudentGraduateDTO->getGraduationCeremoniesId(),
                )->onQueue('import');
            }

            return $excelImportFile;
        } catch (Exception $exception) {
            Log::error('Error store student graduate action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);
            throw new CreateResourceFailedException();
        }


    }
}
