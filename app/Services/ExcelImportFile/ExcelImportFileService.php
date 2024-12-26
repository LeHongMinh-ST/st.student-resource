<?php

declare(strict_types=1);

namespace App\Services\ExcelImportFile;

use App\DTO\AdmissionYear\ListStudentImportDTO;
use App\DTO\ExcelImportFile\ImportStudentFileTypeDTO;
use App\DTO\ExcelImportFile\ListExcelImportFileDTO;
use App\Enums\AuthApiSection;
use App\Enums\ExcelImportType;
use App\Enums\UserRole;
use App\Exceptions\CreateResourceFailedException;
use App\Jobs\CreateStudentByFileCsvJob;
use App\Jobs\CreateStudentGraduateByFileCsvJob;
use App\Jobs\CreateStudentQuitByFileCsvJob;
use App\Jobs\CreateStudentWarningByFileCsvJob;
use App\Models\ExcelImportFile;
use App\Models\ExcelImportFileError;
use App\Models\Faculty;
use App\Supports\Constants;
use App\Supports\ExcelFileHelper;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelImportFileService
{
    public function getListStudentFileImports(ListStudentImportDTO $listStudentImportDTO): Collection|LengthAwarePaginator|array
    {
        $auth = auth(AuthApiSection::Admin->value)->user();
        $query = ExcelImportFile::query()
            ->where('faculty_id', $auth?->faculty_id ?? null)
            ->when(UserRole::Admin !== $auth?->role, fn ($query) => $query->where('user_id', $auth?->id ?? null))
            ->when($listStudentImportDTO->getAdmissionYearId(), fn ($query) => $query->where('type_id', $listStudentImportDTO->getAdmissionYearId())
                ->where('type', ExcelImportType::Course))
            ->when($listStudentImportDTO->getGraduationCeremoniesId(), fn ($query) => $query->where('type_id', $listStudentImportDTO->getGraduationCeremoniesId())
                ->where('type', ExcelImportType::Graduate))
            ->with(['user', 'excelImportFileErrors', 'excelImportFileJobs'])
            ->orderBy($listStudentImportDTO->getOrderBy(), $listStudentImportDTO->getOrder()->value);

        return $listStudentImportDTO->getPage() ? $query->paginate($listStudentImportDTO->getLimit()) : $query->get();
    }

    public function getListFileImports(ListExcelImportFileDTO $listStudentImportDTO): Collection|LengthAwarePaginator|array
    {
        $auth = auth(AuthApiSection::Admin->value)->user();
        $query = ExcelImportFile::query()
            ->where('faculty_id', $auth?->faculty_id ?? null)
            ->when(UserRole::Admin !== $auth?->role, fn ($query) => $query->where('user_id', $auth?->id ?? null))
            ->when($listStudentImportDTO->getType() && $listStudentImportDTO->getTypeId(), fn ($query) => $query->where('type', $listStudentImportDTO->getType())
                ->where('type_id', $listStudentImportDTO->getTypeId()))
            ->with(['user', 'excelImportFileErrors', 'excelImportFileJobs'])
            ->withCount('excelImportFileErrors')
            ->orderBy($listStudentImportDTO->getOrderBy(), $listStudentImportDTO->getOrder()->value);

        return $listStudentImportDTO->getPage() ? $query->paginate($listStudentImportDTO->getLimit()) : $query->get();
    }

    public function getErrorRecord($id): Collection|array
    {
        return ExcelImportFileError::query()->where('excel_import_files_id', $id)->get();
    }

    public function exportErrorRecord($id, $filename = 'error_record.xlsx'): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $errorRecord = $this->getErrorRecord($id);

        $sheet->setCellValue('A1', 'STT');
        $sheet->setCellValue('B1', 'Lỗi');

        $row = 2; //Start row data = 2
        foreach ($errorRecord as $error) {
            $sheet->setCellValue('A' . $row, $error->row - 1);
            $sheet->setCellValue('B' . $row, $error->error);
            $row++;
        }

        $response = new StreamedResponse(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function downloadFileImportTemplate(ExcelImportType $type): array
    {
        $filePathTemplate = match ($type) {
            ExcelImportType::Graduate => public_path() . '/template/template_graduation_student.xlsx',
            ExcelImportType::Course => public_path() . '/template/template_student.xlsx',
            ExcelImportType::Warning => public_path() . '/template/template_student_warning.xlsx',
            ExcelImportType::Quit => public_path() . '/template/template_student_quit.xlsx',
            default => throw new Exception('Type not found'),
        };

        return [
            'path_file' => $filePathTemplate,
            'name_file' => 'template-import-' . $type->value . '.xlsx',
        ];
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function importStudent(ImportStudentFileTypeDTO $importStudentGraduateDTO)
    {
        try {
            $data = ExcelFileHelper::chunkFileToCsv($importStudentGraduateDTO->getFile(), ExcelImportType::Graduate);

            $excelImportFile = ExcelImportFile::create([
                'name' => $importStudentGraduateDTO->getFile()->getClientOriginalName(),
                'type' => $importStudentGraduateDTO->getType(),
                'total_record' => $data['total_row_data'] - Constants::getNumberRowNotRecord(),
                'process_record' => 0,
                'user_id' => auth(AuthApiSection::Admin->value)->id(),
                'faculty_id' => auth(AuthApiSection::Admin->value)->user()?->faculty_id,
                'type_id' => $importStudentGraduateDTO->getTypeId(),
                'total_job' => count($data['file_names']),
            ]);

            try {
                foreach ($data['file_names'] as $fileName) {
                    // Create a new job to import student data
                    $this->handleJobCreateDataByFile(
                        $importStudentGraduateDTO->getType(),
                        $importStudentGraduateDTO->getTypeId(),
                        $fileName,
                        $excelImportFile->id,
                        auth(AuthApiSection::Admin->value)->user()->faculty,
                        auth(AuthApiSection::Admin->value)->id(),
                    );
                }
            } catch (Exception $exception) {
                Log::error('Error dispatch job import student ' . $importStudentGraduateDTO->getType()->value, [
                    'method' => __METHOD__,
                    'message' => $exception->getMessage(),
                ]);
            }

            return $excelImportFile;
        } catch (Exception $exception) {
            Log::error('Error store action student ' . $importStudentGraduateDTO->getType()->value, [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);
            throw new CreateResourceFailedException();
        }
    }

    private function handleJobCreateDataByFile(ExcelImportType $excelImportType, int $entityId, string $fileName, int $excelImportFileId, Faculty $faculty, int $userId): void
    {
        match ($excelImportType) {
            ExcelImportType::Graduate => CreateStudentGraduateByFileCsvJob::dispatch(
                fileName: $fileName,
                excelImportFileId: $excelImportFileId,
                faculty: $faculty,
                userId: $userId,
                graduationCeremonyId: $entityId,
            )->onQueue('import'),
            ExcelImportType::Course => CreateStudentByFileCsvJob::dispatch(
                fileName: $fileName,
                excelImportFileId: $excelImportFileId,
                faculty: $faculty,
                admissionYearId: $entityId,
                userId: $userId,
            )->onQueue('import'),
            ExcelImportType::Warning => CreateStudentWarningByFileCsvJob::dispatch(
                fileName: $fileName,
                excelImportFileId: $excelImportFileId,
                faculty: $faculty,
                userId: $userId,
                warningId: $entityId,
            )->onQueue('import'),
            ExcelImportType::Quit => CreateStudentQuitByFileCsvJob::dispatch(
                fileName: $fileName,
                excelImportFileId: $excelImportFileId,
                faculty: $faculty,
                userId: $userId,
                quitId: $entityId,
            )->onQueue('import'),

            default => throw new Exception('Can not found job with type'),
        };
    }
}
