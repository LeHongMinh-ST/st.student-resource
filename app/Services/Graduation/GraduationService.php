<?php

declare(strict_types=1);

namespace App\Services\Graduation;

use App\DTO\Graduation\CreateGraduationDTO;
use App\DTO\Graduation\ImportStudentGraduateDTO;
use App\DTO\Graduation\ListGraduationDTO;
use App\DTO\Graduation\UpdateGraduationDTO;
use App\Enums\AuthApiSection;
use App\Enums\ExcelImportType;
use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\DeleteResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Jobs\CreateStudentGraduateByFileCsvJob;
use App\Models\ExcelImportFile;
use App\Models\GraduationCeremony;
use App\Models\GraduationCeremonyStudent;
use App\Supports\Constants;
use App\Supports\ExcelFileHelper;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GraduationService
{
    public function getList(ListGraduationDTO $graduationDTO): Collection|LengthAwarePaginator|array
    {
        $query = GraduationCeremonyStudent::query()
            ->when($graduationDTO->getSchoolYear(), fn ($query) => $query->where('school_year', $graduationDTO->getSchoolYear()))
            ->when($graduationDTO->getCertification(), fn ($query) => $query->where('certification', $graduationDTO->getCertification()))
            ->where('faculty_id', '=', auth()->user()->faculty_id ?? null)
            ->withCount('students')
            ->orderBy($graduationDTO->getOrderBy(), $graduationDTO->getOrder()->value);


        return $graduationDTO->getPage() ? $query->paginate($graduationDTO->getLimit()) : $query->get();
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function create(CreateGraduationDTO $graduationDTO): GraduationCeremony
    {
        try {
            return GraduationCeremony::create([
                $graduationDTO->toArray(),
                'faculty_id' => auth(AuthApiSection::Admin->value)->user()->faculty_id
            ]);
        } catch (Exception $exception) {
            Log::error('Error create graduation ceremony action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            throw  new CreateResourceFailedException();
        }
    }

    /**
     * @throws UpdateResourceFailedException
     */
    public function update(UpdateGraduationDTO $graduationDTO): GraduationCeremony
    {
        try {
            $graduation = GraduationCeremony::where('id', $graduationDTO->getId())->first();

            $graduation->fill($graduationDTO->toArray());

            $graduation->save();

            return $graduation;
        } catch (Exception $exception) {
            Log::error('Error update graduation ceremony action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            throw  new UpdateResourceFailedException();
        }
    }

    /**
     * @throws DeleteResourceFailedException
     */
    public function delete(GraduationCeremony $graduation): bool
    {
        try {
            return $graduation->delete();
        } catch (Exception $exception) {
            Log::error('Error delete graduation ceremony action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            throw  new DeleteResourceFailedException();
        }
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
                'total_job' => count($data['file_names'])
            ]);

            foreach ($data['file_names'] as $fileName) {
                // Create a new job to import student data
                CreateStudentGraduateByFileCsvJob::dispatch(
                    fileName: $fileName,
                    excelImportFileId: $excelImportFile->id,
                    faculty: auth(AuthApiSection::Admin->value)->user()->faculty,
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
