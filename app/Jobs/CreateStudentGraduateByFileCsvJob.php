<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DTO\GeneralClass\CreateGeneralClassDTO;
use App\DTO\Student\UpdateStudentInfoByFileGraduationDTO;
use App\Enums\ClassType;
use App\Enums\Status;
use App\Enums\StudentStatus;
use App\Events\ImportStudentGraduateEvent;
use App\Factories\Student\CreateStudentByFileGraduationDTOFactory;
use App\Models\AdmissionYear;
use App\Models\ExcelImportFile;
use App\Models\ExcelImportFileError;
use App\Models\ExcelImportFileJob;
use App\Models\Faculty;
use App\Models\GraduationCeremonyStudent;
use App\Models\Student;
use App\Models\TrainingIndustry;
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
use Illuminate\Support\Facades\DB;

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
        GraduationCeremonyStudent $graduationCeremonyStudent,
        GeneralClassService $generalClassService,
        AdmissionYear $admissionYearModel,
        StudentService $studentService,
        TrainingIndustry $trainingIndustryModel
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

        // get list admissionYear
        $listAdmissionYearValue = collect($listRowMapKey)->pluck('code')
            ->map(
                fn ($item) => mb_substr($item, 0, 2),
            )->toArray();

        $admissionYearModel->upsert(collect($listAdmissionYearValue)->map(fn ($item) => [
            'admission_year' => $item,
        ])->toArray(), uniqueBy: ['admission_year'], update: ['admission_year']);

        $listAdmissionYear = $admissionYearModel->whereIn('admission_year', $listAdmissionYearValue)->get();
        $listTrainingIndustry = $trainingIndustryModel->whereIn('code', Arr::pluck($listRowMapKey, 'training_industry_code'))->get();

        foreach ($listRowMapKey as $rowMapKey) {

            DB::beginTransaction();
            try {
                $student = $listStudent->where('code', $rowMapKey['code'])->first();

                $studentData = [
                    'training_industry_id' => $listTrainingIndustry->where('code', $rowMapKey['training_industry_code'])->first()?->id,
                    'faculty_id' => $this->faculty->id,
                    'full_name' => $rowMapKey['full_name'],
                    'person_email' => Arr::get($rowMapKey, 'email'),
                    'code' => $rowMapKey['code'],
                    'gender' => Arr::get($rowMapKey, 'gender') ?? '',
                    'phone_number' => Arr::get($rowMapKey, 'phone_number'),
                    'dob' => Arr::get($rowMapKey, 'dob'),
                    'address' => Arr::get($rowMapKey, 'address'),
                    'citizen_identification' => Arr::get($rowMapKey, 'identity_card'),
                    'admission_year_id' => $listAdmissionYear->where('admission_year', mb_substr($rowMapKey['code'], 0, 2))->first()?->id,
                ];

                if (null === $student) {
                    // create student with info
                    $commandStudentWithInfo = CreateStudentByFileGraduationDTOFactory::make($studentData);
                    $student = $studentService->createWithInfoStudentByFile($commandStudentWithInfo);
                } else {
                    $student->status = StudentStatus::Graduated;
                    if (Arr::get($studentData, 'training_industry_id')) {
                        $student->training_industry_id = Arr::get($studentData, 'training_industry_id');
                    }
                    $student->save();

                    $dataUpdateInfoStudent = new UpdateStudentInfoByFileGraduationDTO(
                        $studentData
                    );

                    $student->info()->update($dataUpdateInfoStudent->toArray());
                }

                // check student does not have graduation ceremony
                if (! $student->graduationCeremonies->isEmpty()
                    && $student->graduationCeremonies->where('id', $this->graduationCeremonyId)->isEmpty()
                ) {
                    throw new Exception('Sinh viên có trong đợt tốt nghiệp khác');
                }

                // Nếu co du lieu lop va sv chua co trong lop do
                if (Arr::get($rowMapKey, 'class_name') && $student->generalClass->where('code', $rowMapKey['class_name'])->isEmpty()) {
                    $commandClass = new CreateGeneralClassDTO([
                        'name' => $this->faculty->name,
                        'code' => $rowMapKey['class_name'],
                        'faculty_id' => $this->faculty->id,
                        'type' => ClassType::Major->value,
                    ]);

                    $generalClass = $generalClassService->getGeneralClassByCode($commandClass->getCode());
                    if (null === $generalClass) {
                        $generalClass = $generalClassService->create($commandClass);
                    }

                    // Sync student with class
                    $generalClass->students()->syncWithoutDetaching([
                        $student->id => [
                            'status' => Status::Enable->value,
                            'start_date' => now(),
                        ],
                    ]);
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
        if (! $hasError) {
            $this->deleteFile($filePath);
        }

        event(new ImportStudentGraduateEvent(
            message: 'success',
            userId: $this->userId
        ));
    }
}
