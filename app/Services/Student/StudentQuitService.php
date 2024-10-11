<?php

declare(strict_types=1);

namespace App\Services\Student;

use App\DTO\Quit\CreateStudentQuitDTO;
use App\DTO\Quit\ImportStudentQuitDTO;
use App\DTO\Quit\ListStudentQuitDTO;
use App\DTO\Quit\UpdateStudentQuitDTO;
use App\Enums\AuthApiSection;
use App\Enums\ExcelImportType;
use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\DeleteResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Jobs\CreateStudentQuitByFileCsvJob;
use App\Models\ExcelImportFile;
use App\Models\Quit;
use App\Supports\Constants;
use App\Supports\ExcelFileHelper;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class StudentQuitService
{
    public function getList(ListStudentQuitDTO $dto): Collection|LengthAwarePaginator|array
    {
        $query = Quit::query()
            ->when($dto->getSemesterId(), fn ($query) => $query->where('semester_id', $dto->getSemesterId()))
            ->where('faculty_id', '=', auth()->user()->faculty_id ?? null)
            ->orderBy($dto->getOrderBy(), $dto->getOrder()->value);


        return $dto->getPage() ? $query->paginate($dto->getLimit()) : $query->get();
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function create(CreateStudentQuitDTO $dto): Quit
    {
        try {
            return Quit::create($dto->toArray());
        } catch (Exception $exception) {
            Log::error('Error create quit student action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);
            throw new CreateResourceFailedException();
        }
    }

    /**
     * @throws UpdateResourceFailedException
     */
    public function update(UpdateStudentQuitDTO $dto): Quit
    {
        try {
            $data = Quit::where('id', $dto->getId())->first();

            $data->fill($dto->toArray());

            $data->save();

            return $data;
        } catch (Exception $exception) {
            Log::error('Error update quit student action', [
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
            Quit::destroy($id);
        } catch (Exception $exception) {
            Log::error('Error delete quit student action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            throw new DeleteResourceFailedException();
        }
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function importStudentQuit(ImportStudentQuitDTO $dto): ExcelImportFile
    {
        try {
            $data = ExcelFileHelper::chunkFileToCsv($dto->getFile(), ExcelImportType::Quit);
            $excelImportFile = ExcelImportFile::create([
                'name' => $dto->getFile()->getClientOriginalName(),
                'type' => ExcelImportType::Quit,
                'total_record' => $data['total_row_data'] - Constants::getNumberRowNotRecord(),
                'process_record' => 0,
                'faculty_id' => auth(AuthApiSection::Admin->value)->user()?->faculty_id,
                'user_id' => auth(AuthApiSection::Admin->value)->id(),
                'type_id' => $dto->getQuitId(),
                'total_job' => count($data['file_names'])
            ]);
            foreach ($data['file_names'] as $fileName) {
                CreateStudentQuitByFileCsvJob::dispatch(
                    fileName: $fileName,
                    excelImportFileId: $excelImportFile->id,
                    faculty: auth(AuthApiSection::Admin->value)->user()->faculty,
                    userId: auth(AuthApiSection::Admin->value)->id(),
                    quitId: $dto->getQuitId(),
                )->onQueue('import');
            }

            return $excelImportFile;
        } catch (Exception $exception) {
            Log::error('Error import quit student action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);
            throw new CreateResourceFailedException();
        }
    }
}
