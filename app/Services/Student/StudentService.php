<?php

declare(strict_types=1);

namespace App\Services\Student;

use App\DTO\Student\CreateStudentCourseByFileDTO;
use App\DTO\Student\CreateStudentDTO;
use App\DTO\Student\ImportCourseStudentDTO;
use App\DTO\Student\ListStudentDTO;
use App\DTO\Student\UpdateStudentDTO;
use App\Enums\AuthApiSection;
use App\Enums\ExcelImportType;
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
            // check if student already exists
            if (Student::where('code', $command->getCode())->exists()) {
                throw new CreateResourceFailedException('Student already exists');
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
                'total_job' => count($data['file_names'])
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
}
