<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DTO\GeneralClass\CreateGeneralClassDTO;
use App\Enums\Status;
use App\Events\ImportStudentCourseEvent;
use App\Factories\Student\CreateStudentByFileDTOFactory;
use App\Models\ExcelImportFile;
use App\Models\ExcelImportFileError;
use App\Models\ExcelImportFileJob;
use App\Models\Faculty;
use App\Services\GeneralClass\GeneralClassService;
use App\Services\Student\StudentService;
use App\Traits\HandlesCsvImportJob;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class CreateStudentByFileCsvJob implements ShouldQueue
{
    use Dispatchable;
    use HandlesCsvImportJob;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string  $fileName,
        private readonly int     $excelImportFileId,
        private readonly Faculty $faculty,
        private readonly int     $admissionYearId,
        private readonly int     $userId,
    ) {
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(
        ExcelImportFile      $excelImportFileModel,
        ExcelImportFileError $excelImportFileErrorModel,
        ExcelImportFileJob   $excelImportFileJobModel,
        GeneralClassService  $generalClassService,
        StudentService       $studentService
    ): void {
        // Store job into the database if a Job ID exists.
        $this->storeJob($this->job->getJobId(), $this->excelImportFileId, $excelImportFileJobModel);


        // Get file path and load CSV file content.
        $filePath = $this->getFilePath($this->fileName);
        $rowStart = $this->getRowStart($this->fileName);
        $worksheet = $this->loadCsvFile($filePath);

        // Get row header
        $rowHeader = Arr::first($worksheet);
        $hasError = false;

        // Remove row header from worksheet
        array_shift($worksheet);

        foreach ($worksheet as $row) {
            try {
                $studentData = [
                    'faculty_id' => $this->faculty->id,
                    'admission_year_id' => $this->admissionYearId,
                ];

                // map key column with value
                foreach ($row as $index => $value) {
                    // continue with key not in key config file
                    if (null === $rowHeader[$index]) {
                        continue;
                    }
                    $studentData[$rowHeader[$index]] = $value;
                }

                // Check exist class if not exist create new class
                $commandClass = new CreateGeneralClassDTO([
                    'name' => $this->faculty->name,
                    'code' => $studentData['class_code'],
                    'faculty_id' => $this->faculty->id,
                ]);

                // get class or create new class
                $generalClass = $generalClassService->getGeneralClassByCode($commandClass->getCode());
                if (null === $generalClass) {
                    $generalClass = $generalClassService->create($commandClass);
                }

                // create student with info
                $commandStudentWithInfo = CreateStudentByFileDTOFactory::make($studentData);
                $student = $studentService->createWithInfoStudentByFile($commandStudentWithInfo);

                // create data excel import file record
                $student->excelImportFileRecord()->create([
                    'excel_import_files_id' => $this->excelImportFileId,
                ]);

                // Sync student with class
                $generalClass->students()->syncWithoutDetaching([
                    $student->id => [
                        'status' => Status::Enable->value,
                        'start_date' => now(),
                    ],
                ]);

                // Update total process record
                $excelImportFileModel->where('id', $this->excelImportFileId)
                    ->increment('process_record');

            } catch (Exception $exception) {
                $hasError = true;
                $this->handleException($exception, $rowStart, $excelImportFileErrorModel, $this->excelImportFileId);
            }
            $rowStart++;
        }

        // Delete the file if no errors occurred.
        if (!$hasError) {
            $this->deleteFile($filePath);
        }

        event(new ImportStudentCourseEvent(
            message: 'success',
            userId: $this->userId
        ));
    }
}
