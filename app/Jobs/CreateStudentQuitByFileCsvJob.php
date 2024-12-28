<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\StudentStatus;
use App\Events\ImportStudentQuitEvent;
use App\Models\ExcelImportFile;
use App\Models\ExcelImportFileError;
use App\Models\ExcelImportFileJob;
use App\Models\Faculty;
use App\Models\Quit;
use App\Models\StudentQuit;
use App\Services\Student\StudentService;
use App\Traits\HandlesCsvImportJob;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateStudentQuitByFileCsvJob implements ShouldQueue
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
        private readonly int     $quitId,
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
        StudentService       $studentService
    ): void {

        // Get file path and load CSV file content.
        $filePath = $this->getFilePath($this->fileName);
        $rowStart = $this->getRowStart($this->fileName);
        $worksheet = $this->loadCsvFile($filePath);

        // Get row header
        $rowHeader = Arr::first($worksheet);
        $listRowMapKey = collect($worksheet)->map(fn ($item) => array_combine($rowHeader, $item))->toArray();
        $hasError = false;

        // Remove row header from worksheet
        array_shift($worksheet);
        foreach ($listRowMapKey as $row) {
            DB::beginTransaction();
            try {
                $student = $studentService->getStudentByCode($row['code']);

                // Prepare data for GraduationCeremonyStudent table.
                $studentQuitData = [
                    'quit_id' => $this->quitId,
                    'student_id' => $student->id,
                ];

                $studentQuit = StudentQuit::query()
                    ->updateOrCreate($studentQuitData);

                $studentQuit->note_quit = @$row['note_quit'];
                $studentQuit->save();
                $quit = Quit::find($this->quitId);

                if ($quit->type === StudentStatus::Expelled->value) {
                    $student->status = StudentStatus::Expelled;
                }

                if ($quit->type === StudentStatus::Deferred->value) {
                    $student->status = StudentStatus::Deferred;
                }

                if ($quit->type === StudentStatus::ToDropOut->value) {
                    if (@$row['type']) {
                        Log::info('Check status ....check type.....');
                        if ('NH' === $row['type']) {
                            $student->status = StudentStatus::ToDropOut;
                        }

                        if ('CN' === $row['type']) {
                            $student->status = StudentStatus::TransferStudy;
                        }
                    }
                }

                $student->save();

                $studentQuit->excelImportFileRecord()->create([
                    'excel_import_files_id' => $this->excelImportFileId,
                ]);

                // Log successful process.
                $excelImportFileModel->where('id', $this->excelImportFileId)
                    ->increment('process_record');

                DB::commit();

            } catch (Exception $exception) {
                DB::rollback();
                $hasError = true;
                $this->handleException($exception, $rowStart, $excelImportFileErrorModel, $this->excelImportFileId);
            }
            $rowStart++;
        }

        // Store job into the database if a Job ID exists.
        $this->storeJob($this->job->getJobId(), $this->excelImportFileId, $excelImportFileJobModel);


        // Delete the file if no errors occurred.
        if (!$hasError) {
            $this->deleteFile($filePath);
        }

        event(new ImportStudentQuitEvent(
            message: 'success',
            userId: $this->userId
        ));
    }
}
