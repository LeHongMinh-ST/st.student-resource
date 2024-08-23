<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Domain\GeneralClass\Actions\CreateGeneralClassAction;
use App\Domain\GeneralClass\Commands\GeneralClassCreateCommand;
use App\Domain\Student\Actions\CreateStudentWithInfoByFileAction;
use App\Domain\Student\Factories\CreateStudentByFileCommandFactory;
use App\Enums\Status;
use App\Models\ExcelImportFile;
use App\Models\ExcelImportFileError;
use App\Models\ExcelImportFileJob;
use App\Models\Faculty;
use App\Models\GeneralClass;
use App\Supports\Constants;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CreateStudentByFileCsvJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $fileName,
        private readonly int    $excelImportFileId,
        private Faculty         $faculty
    ) {

    }

    /**
     * Execute the job.
     */
    public function handle(
        GeneralClass          $generalClassModel,
        ExcelImportFile        $excelImportFileModel,
        ExcelImportFileError   $excelImportFileErrorModel,
        ExcelImportFileJob    $excelImportFileJobModel,
    ): void {

        // store job if run sync
        if ($this->job->getJobId()) {
            $excelImportFileJobModel::create([
                'excel_import_file_id' => $this->excelImportFileId,
                'job_id' => $this->job->getJobId(),
            ]);
        }

        // Get the file path from the storage
        $filePath = storage_path('app/public/' . Constants::PATH_FILE_IMPORT_COURSE . $this->fileName);
        $rowStart = str_replace('.csv', '', Arr::last(explode('_', $this->fileName))) * 1;

        // Check if the file exists
        if (! file_exists($filePath)) {
            throw new Exception('File not found file path: ' . $filePath);
        }

        $worksheet = IOFactory::load($filePath)->getActiveSheet()->toArray();

        // Get row header
        $rowHeader = Arr::first($worksheet);
        $hasError = false;

        // Remove row header from worksheet
        array_shift($worksheet);

        foreach ($worksheet as $row) {
            try {
                $studentData = [
                    'faculty_id' => $this->faculty->id,
                ];

                // map key column with value
                foreach ($row as $index => $value) {
                    $studentData[$rowHeader[$index]] = $value;
                }

                // Check exist class if not exist create new class
                $commandClass = new GeneralClassCreateCommand([
                    'name' => $this->faculty->name,
                    'code' => $studentData['class_code'],
                    'faculty_id' => $this->faculty->id,
                ]);

                // get class or create new class
                $generalClass = $generalClassModel->where('code', $commandClass->getCode())->first();
                if (null === $generalClass) {
                    $generalClass = app()->make(CreateGeneralClassAction::class)->execute($commandClass);
                }

                // create student with info
                $commandStudentWithInfo = CreateStudentByFileCommandFactory::make($studentData);
                $student = app()->make(CreateStudentWithInfoByFileAction::class)->execute($commandStudentWithInfo);

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
                $excelImportFileModel->where('id', $this->excelImportFileId)->increment('process_record');

            } catch (Exception $exception) {
                $hasError = true;
                // Log any errors that occur during the role creation process
                Log::error('Error store student with info action', [
                    'method' => __METHOD__,
                    'message' => $exception->getMessage(),
                ]);

                // store error message to excel_import_files table
                $excelImportFileErrorModel->create([
                    'excel_import_files_id' => $this->excelImportFileId,
                    'row' => $rowStart,
                    'error' => $exception->getMessage(),
                ]);
            }
            $rowStart++;
        }

        if (! $hasError) {
            // remove file from name file
            unlink($filePath);
        }
    }
}
