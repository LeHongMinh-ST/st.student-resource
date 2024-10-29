<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\StudentStatus;
use App\Events\ImportStudentGraduateEvent;
use App\Models\ExcelImportFile;
use App\Models\ExcelImportFileError;
use App\Models\ExcelImportFileJob;
use App\Models\Faculty;
use App\Models\GraduationCeremonyStudent;
use App\Models\Student;
use App\Traits\HandlesCsvImportJob;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class CreateStudentGraduateByFileCsvJob implements ShouldQueue
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
        private readonly int     $userId,
        private readonly int     $graduationCeremonyId,
    ) {
    }

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    public function handle(
        ExcelImportFile      $excelImportFileModel,
        ExcelImportFileError $excelImportFileErrorModel,
        ExcelImportFileJob   $excelImportFileJobModel,
        GraduationCeremonyStudent $graduationCeremonyStudent
    ): void {
        // Get file path and load CSV file content.
        $filePath = $this->getFilePath($this->fileName);
        $rowStart = $this->getRowStart($this->fileName);
        $worksheet = $this->loadCsvFile($filePath);

        // Get row header
        $rowHeader = Arr::first($worksheet);
        $hasError = false;

        // Remove row header from worksheet
        array_shift($worksheet);

        // Map row data with row header
        $listRowMapKey = collect($worksheet)->map(fn ($item) => array_combine($rowHeader, $item))->toArray();
        $listStudent = Student::with(['graduationCeremonies'])->whereIn('code', Arr::pluck($listRowMapKey, 'code'))->get();

        foreach ($listRowMapKey as $rowMapKey) {
            try {
                $student = $listStudent->where('code', $rowMapKey['code'])->first();
                if (null === $student) {
                    throw new Exception('Student not found');
                }
                if ($student->graduationCeremonies->count()) {
                    throw new Exception('Student has graduated');
                }

                $graduationCeremonyStudentData = [
                    'graduation_ceremony_id' => $this->graduationCeremonyId,
                    'student_id' => $student->id,
                ];
                // Prepare data for GraduationCeremonyStudent table.
                $rowMapKey = array_merge($rowMapKey, $graduationCeremonyStudentData);

                $graduationCeremonyStudent->updateOrCreate(
                    $graduationCeremonyStudentData,
                    Arr::only($rowMapKey, $graduationCeremonyStudent->getFillable())
                );

                // Update student status.
                $student->status = StudentStatus::Graduated;
                $student->save();

                // Log successful process.
                $excelImportFileModel->where('id', $this->excelImportFileId)
                    ->increment('process_record');

            } catch (Exception $exception) {
                $hasError = true;
                $this->handleException($exception, $rowStart, $excelImportFileErrorModel, $this->excelImportFileId);
            }
            $rowStart++;
        }
        // Store job into the database if a Job ID exists.
        $this->storeJob($this->job->getJobId(), $this->excelImportFileId, $excelImportFileJobModel);

        // Delete the file if no errors occurred.
        if (! $hasError) {
            $this->deleteFile($filePath);
        }

        event(new ImportStudentGraduateEvent(
            message: 'success',
            userId: $this->userId
        ));
    }
}
