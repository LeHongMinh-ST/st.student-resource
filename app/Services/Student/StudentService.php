<?php

declare(strict_types=1);

namespace App\Services\Student;

use App\DTO\Student\CreateStudentCourseByFileDTO;
use App\DTO\Student\CreateStudentDTO;
use App\DTO\Student\ImportCourseStudentDTO;
use App\DTO\Student\ListStudentDTO;
use App\DTO\Student\ListStudentSurveyDTO;
use App\DTO\Student\UpdateStudentDTO;
use App\Enums\AuthApiSection;
use App\Enums\ExcelImportType;
use App\Enums\StudentStatus;
use App\Enums\UserRole;
use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Jobs\CreateStudentByFileCsvJob;
use App\Models\ExcelImportFile;
use App\Models\Student;
use App\Services\Student\StudentInfo\StudentInfoService;
use App\Supports\Constants;
use App\Supports\ExcelFileHelper;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentService
{
    public function __construct(private readonly StudentInfoService $studentInfoService)
    {
    }

    public function getList(ListStudentDTO $listStudentDTO): Collection|LengthAwarePaginator|array
    {
        $query = Student::query()
            ->when($listStudentDTO->getAdmissionYearId(), fn ($q) => $q->where('admission_year_id', $listStudentDTO->getAdmissionYearId()))
            ->when(
                $listStudentDTO->getQ(),
                fn ($q) => $q
                    ->where(DB::raw("CONCAT(last_name, ' ', first_name)"), 'like', '%' . $listStudentDTO->getQ() . '%')
                    ->orWhere('email', 'like', '%' . $listStudentDTO->getQ() . '%')
                    ->orWhere('code', 'like', '%' . $listStudentDTO->getQ() . '%')
            )
            ->when($listStudentDTO->getGraduationId(), fn ($q) => $q->whereHas('graduationCeremonies', fn ($q) => $q->where('graduation_ceremony_id', $listStudentDTO->getGraduationId())))
            ->when($listStudentDTO->getStatus(), fn ($q) => $q->where('status', $listStudentDTO->getStatus()))
            ->where('faculty_id', '=', auth()->user()->faculty_id ?? null)
            ->when($listStudentDTO->getClassId(), fn ($q) => $q->whereHas('generalClass', fn ($q) => $q->where('classes.id', $listStudentDTO->getClassId())))
            ->with(['info', 'currentClass', 'families', 'graduationCeremonies'])
            ->orderBy($listStudentDTO->getOrderBy(), $listStudentDTO->getOrder()->value);

        return $listStudentDTO->getPage() ? $query->paginate($listStudentDTO->getLimit()) : $query->get();
    }

    public function getBySurveyPeriod(ListStudentSurveyDTO $listStudentDTO): Collection|LengthAwarePaginator|array
    {
        $query = Student::query()->select(['students.*', 'employment_survey_responses.created_at as response_created_at'])
            ->with(['info', 'currentSurvey', 'currentClass', 'activeResponseSurvey.trainingIndustry', 'activeResponseSurvey.cityWork'])
            ->leftJoin('employment_survey_responses', 'students.id', '=', 'employment_survey_responses.student_id')
            ->when(
                $listStudentDTO->getSurveyPeriodId(),
                fn ($q) => $q->whereHas('surveyPeriods', fn ($q) => $q->where('survey_period_student.survey_period_id', $listStudentDTO->getSurveyPeriodId()))
            )
            ->when(null !== $listStudentDTO->getIsResponse(), function ($q) use ($listStudentDTO) {
                if (1 === $listStudentDTO->getIsResponse()) {
                    return $q->whereHas('employmentSurveyResponses', fn ($q) => $q->where('employment_survey_responses.survey_period_id', $listStudentDTO->getSurveyPeriodId()));
                }

                return $q->whereDoesntHave('employmentSurveyResponses', fn ($q) => $q->where('employment_survey_responses.survey_period_id', $listStudentDTO->getSurveyPeriodId()));
            })
            ->when($listStudentDTO->getStatus(), fn ($q) => $q->where('status', $listStudentDTO->getStatus()))
            ->where('faculty_id', '=', auth()->user()->faculty_id ?? null)
            ->when(
                $listStudentDTO->getQ(),
                fn ($q) => $q->where(function ($q) use ($listStudentDTO) {
                    return $q->where(DB::raw("CONCAT(last_name, ' ', first_name)"), 'like', '%' . $listStudentDTO->getQ() . '%')
                        ->orWhere('code', 'like', '%' . $listStudentDTO->getQ() . '%');
                })
            )

            ->orderBy('response_created_at', $listStudentDTO->getOrder()->value);

        return $listStudentDTO->getPage() ? $query->paginate($listStudentDTO->getLimit()) : $query->get();
    }

    public function getTotalStudent(ListStudentDTO $listStudentDTO): int
    {
        return Student::query()
            ->when($listStudentDTO->getAdmissionYearId(), fn ($q) => $q->where('admission_year_id', $listStudentDTO->getAdmissionYearId()))
            ->when(
                $listStudentDTO->getQ(),
                fn ($q) => $q
                    ->where(DB::raw("CONCAT(last_name, ' ', first_name)"), 'like', '%' . $listStudentDTO->getQ() . '%')
                    ->orWhere('email', 'like', '%' . $listStudentDTO->getQ() . '%')
                    ->orWhere('code', 'like', '%' . $listStudentDTO->getQ() . '%')
            )
            ->when($listStudentDTO->getStatus(), fn ($q) => $q->where('status', $listStudentDTO->getStatus()))
            ->where('faculty_id', '=', auth()->user()->faculty_id ?? null)
            ->when($listStudentDTO->getClassId(), fn ($q) => $q->whereHas('generalClass', fn ($q) => $q->where('classes.id', $listStudentDTO->getClassId())))
            ->count();
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function createWithInfoStudent(CreateStudentDTO $command): Student
    {
        DB::beginTransaction();
        try {
            // Create a new student using the CreateStudentAction
            $student = Student::create($command->toArray());

            // Create additional student information
            $this->studentInfoService->createByStudent($student, $command);

            // Load additional information into the student object
            DB::commit();

            return $student;
        } catch (Exception $exception) {
            DB::rollBack();
            // Log any errors that occur during the role creation process
            Log::error('Error store student with info action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            // If an exception occurs during student creation, throw a custom exception
            throw new CreateResourceFailedException();
        }
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function createWithInfoStudentByFile(CreateStudentCourseByFileDTO $command): Student
    {
        DB::beginTransaction();
        try {
            $student = Student::query()->where('code', $command->getCode())->first();
            // check if student already exists
            if ($student) {
                throw new CreateResourceFailedException('Student already exists');
                //                $this->studentInfoService->updateByStudentFileCourse($student->id, $command);
            }

            // Create a new student using the CreateStudentAction
            $student = Student::create($command->toArray());

            // Create additional student information
            $this->studentInfoService->createByStudentFileCourse($student, $command);

            // Load additional information into the student object
            DB::commit();

            return $student;
        } catch (Exception $exception) {
            DB::rollBack();
            // Log any errors that occur during the role creation process
            Log::error('Error store student with info action by file import', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            // If an exception occurs during student creation, throw a custom exception
            throw new CreateResourceFailedException(
                message: $exception->getMessage(),
            );
        }
    }

    /**
     * @throws UpdateResourceFailedException
     */
    public function updateWithInfoStudent(UpdateStudentDTO $command): Student
    {
        DB::beginTransaction();
        try {
            // update student using the CreateStudentAction
            $student = Student::where('id', $command->getId())->first();

            $student->update($command->toArray());
            // Create additional student information
            $this->studentInfoService->update($command->getInfoDTO());
            $student->families()->delete();
            foreach ($command->getFamilyStudentDTOArray() as $family) {
                $student->families()->create($family->toArray());
            }


            // Load additional information into the student object
            DB::commit();

            return $student;
        } catch (Exception $exception) {
            DB::rollBack();
            // Log any errors that occur during the role creation process
            Log::error('Error update student with info action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            throw new UpdateResourceFailedException();
        }
    }

    public function delete(mixed $id): bool
    {
        $student = Student::find($id);
        $student->info()->delete();
        $student->families()->delete();

        return $student->delete();
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function importCourse(ImportCourseStudentDTO $courseStudentDTO)
    {
        try {
            $data = ExcelFileHelper::chunkFileToCsv($courseStudentDTO->getFile(), ExcelImportType::Course);

            // Create record in excel_import_files table
            $excelImportFile = ExcelImportFile::create([
                'name' => $courseStudentDTO->getFile()->getClientOriginalName(),
                'type' => ExcelImportType::Course,
                'total_record' => $data['total_row_data'] - Constants::getNumberRowNotRecord(),
                'process_record' => 0,
                'faculty_id' => auth(AuthApiSection::Admin->value)->user()?->faculty_id,
                'user_id' => auth(AuthApiSection::Admin->value)->id(),
                'type_id' => $courseStudentDTO->getAdmissionYearId(),
                'total_job' => count($data['file_names']),
            ]);

            foreach ($data['file_names'] as $fileName) {
                // Create a new job to import student data
                CreateStudentByFileCsvJob::dispatch(
                    fileName: $fileName,
                    excelImportFileId: $excelImportFile->id,
                    faculty: auth(AuthApiSection::Admin->value)->user()->faculty,
                    admissionYearId: $courseStudentDTO->getAdmissionYearId(),
                    userId: auth(AuthApiSection::Admin->value)->id(),
                )->onQueue('import');
            }

            return $excelImportFile;
        } catch (Exception $exception) {
            Log::error('Error store student action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);
            throw new CreateResourceFailedException();
        }
    }

    public function getStudentByCode(string $code): Model|Builder
    {
        return Student::query()->where(['code' => $code])->firstOrFail();
    }

    public function searchOneStudent(array $filter): Model|Builder
    {
        return Student::when(Arr::get($filter, 'code'), fn ($q) => $q->where('code', $filter['code']))
            ->when(Arr::get($filter, 'email'), function ($q) use ($filter) {
                return $q->whereHas('graduationCeremonies', function ($q) use ($filter): void {
                    $q->where('email', $filter['email']);
                });
            })
            ->when(Arr::get($filter, 'phone_number'), function ($q) use ($filter) {
                return $q->whereHas('info', function ($q) use ($filter): void {
                    $q->where('phone_number', $filter['phone_number']);
                });
            })
            ->when(Arr::get($filter, 'code_verify'), function ($q) use ($filter) {
                return $q->whereHas('surveyPeriods', function ($q) use ($filter): void {
                    $q->where('survey_period_student.code_verify', $filter['code_verify']);
                });
            })
            ->with(['info', 'graduationCeremonies'])
            ->first();
    }

    public function getTotalStudentStudy(): int
    {
        $auth = auth(AuthApiSection::Admin->value)->user();

        $studentsCount = Student::query()
            ->where('status', StudentStatus::CurrentlyStudying)
            ->where('faculty_id', $auth->faculty_id)
            ->when(UserRole::Teacher === $auth->role, fn ($q) => $q->whereHas('generalClass', function ($q) {
                return $q->where('teacher_id', auth(AuthApiSection::Admin->value)->id())
                    ->orWhere('sub_teacher_id', auth(AuthApiSection::Admin->value)->id());
            }))
            ->count();

        return $studentsCount;
    }

    public function getTotalStudentGraduated(): int
    {
        $auth = auth(AuthApiSection::Admin->value)->user();

        $studentsCount = Student::query()
            ->where('status', StudentStatus::Graduated)
            ->where('faculty_id', $auth->faculty_id)
            ->when(UserRole::Teacher === $auth->role, fn ($q) => $q->whereHas('generalClass', function ($q) {
                return $q->where('teacher_id', auth(AuthApiSection::Admin->value)->id())
                    ->orWhere('sub_teacher_id', auth(AuthApiSection::Admin->value)->id());
            }))
            ->count();

        return $studentsCount;
    }

    public function getTotalStudentWarning(): int
    {
        $auth = auth(AuthApiSection::Admin->value)->user();

        $latestWarningIds = DB::table('warnings')
            ->orderBy('semester_id', 'desc')
            ->take(2)
            ->pluck('id');

        if (count($latestWarningIds) < 2) {
            return 0;
        }

        return DB::table('students')
            ->join('student_warnings', 'students.id', '=', 'student_warnings.student_id')
            ->whereIn('student_warnings.warning_id', $latestWarningIds)
            ->when(UserRole::Teacher === $auth->role, fn ($q) => $q->whereHas('generalClass', function ($q) {
                return $q->where('teacher_id', auth(AuthApiSection::Admin->value)->id())
                    ->orWhere('sub_teacher_id', auth(AuthApiSection::Admin->value)->id());
            }))
            ->distinct('students.id')
            ->count();

    }

    public function changeStatus($studentId, $status): bool|int
    {
        return Student::query()->findOrFail($studentId)->update(['status' => $status]);
    }
}
