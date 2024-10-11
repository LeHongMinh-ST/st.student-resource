<?php

declare(strict_types=1);

namespace App\Services\Student;

use App\DTO\Warning\CreateStudentWarningDTO;
use App\DTO\Warning\ImportStudentWarningDTO;
use App\DTO\Warning\ListStudentWarningDTO;
use App\DTO\Warning\UpdateStudentWarningDTO;
use App\Enums\AuthApiSection;
use App\Enums\ExcelImportType;
use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\DeleteResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Jobs\CreateStudentWarningByFileCsvJob;
use App\Models\ExcelImportFile;
use App\Models\Warning;
use App\Supports\Constants;
use App\Supports\ExcelFileHelper;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class StudentWarningService
{
    public function getList(ListStudentWarningDTO $dto): Collection|LengthAwarePaginator|array
    {
        $query = Warning::query()
            ->when($dto->getSemesterId(), fn ($query) => $query->where('semester_id', $dto->getSemesterId()))
            ->where('faculty_id', '=', auth()->user()->faculty_id ?? null)
            ->orderBy($dto->getOrderBy(), $dto->getOrder()->value);


        return $dto->getPage() ? $query->paginate($dto->getLimit()) : $query->get();
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function create(CreateStudentWarningDTO $dto): Warning
    {
        try {
            return Warning::create($dto->toArray());
        } catch (Exception $exception) {
            Log::error('Error create warning student action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);
            throw new CreateResourceFailedException();
        }
    }

    /**
     * @throws UpdateResourceFailedException
     */
    public function update(UpdateStudentWarningDTO $dto): Warning
    {
        try {
            $data = Warning::where('id', $dto->getId())->first();

            $data->fill($dto->toArray());

            $data->save();

            return $data;
        } catch (Exception $exception) {
            Log::error('Error update warning student action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);
            throw new UpdateResourceFailedException();
        }
    }

    /**
     * @throws DeleteResourceFailedException
     */
    public function delete(int|string $id): void
    {
        try {
            Warning::destroy($id);
        } catch (Exception $exception) {
            Log::error('Error delete warning student action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            throw new DeleteResourceFailedException();
        }
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function importStudentWarning(ImportStudentWarningDTO $dto): ExcelImportFile
    {
        try {
            $data = ExcelFileHelper::chunkFileToCsv($dto->getFile(), ExcelImportType::Warning);
            $excelImportFile = ExcelImportFile::create([
                'name' => $dto->getFile()->getClientOriginalName(),
                'type' => ExcelImportType::Warning,
                'total_record' => $data['total_row_data'] - Constants::getNumberRowNotRecord(),
                'process_record' => 0,
                'faculty_id' => auth(AuthApiSection::Admin->value)->user()?->faculty_id,
                'user_id' => auth(AuthApiSection::Admin->value)->id(),
                'type_id' => $dto->getWarningId(),
                'total_job' => count($data['file_names'])
            ]);
            foreach ($data['file_names'] as $fileName) {
                CreateStudentWarningByFileCsvJob::dispatch(
                    fileName: $fileName,
                    excelImportFileId: $excelImportFile->id,
                    faculty: auth(AuthApiSection::Admin->value)->user()->faculty,
                    userId: auth(AuthApiSection::Admin->value)->id(),
                    warningId: $dto->getWarningId(),
                )->onQueue('import');
            }

            return $excelImportFile;
        } catch (Exception $exception) {
            Log::error('Error import warning student action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);
            throw new CreateResourceFailedException();
        }
    }
}
