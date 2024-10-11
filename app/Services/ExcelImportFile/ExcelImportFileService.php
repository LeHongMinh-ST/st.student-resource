<?php

declare(strict_types=1);

namespace App\Services\ExcelImportFile;

use App\DTO\AdmissionYear\ListStudentImportDTO;
use App\Enums\AuthApiSection;
use App\Enums\ExcelImportType;
use App\Enums\UserRole;
use App\Models\ExcelImportFile;
use App\Models\ExcelImportFileError;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
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
            ->where('type', ExcelImportType::Course)
            ->when(UserRole::Admin !== $auth?->role, fn($query) => $query->where('user_id', $auth?->id ?? null))
            ->when($listStudentImportDTO->getAdmissionYearId(), fn($query) => $query->where('type_id', $listStudentImportDTO->getAdmissionYearId()))
            ->with(['user', 'excelImportFileErrors'])
            ->orderBy($listStudentImportDTO->getOrderBy(), $listStudentImportDTO->getOrder()->value);

        return $listStudentImportDTO->getPage() ? $query->paginate($listStudentImportDTO->getLimit()) : $query->get();
    }

    public function getErrorRecord($id): Collection|array
    {
        return ExcelImportFileError::query()->where('excel_import_file_id', $id)->get();

    }

    public function exportErrorRecord($id, $filename = 'error_record.xlsx'): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $errorRecord = $this->getErrorRecord($id);

        $sheet->setCellValue('A1', 'STT');
        $sheet->setCellValue('B1', 'Bản ghi số');
        $sheet->setCellValue('C1', 'Lỗi');

        $row = 2; //Start row data = 2
        foreach ($errorRecord as $error) {
            $sheet->setCellValue('A' . $row, $row - 1);
            $sheet->setCellValue('B' . $row, $error->row);
            $sheet->setCellValue('C' . $row, $error->error);
            $row++;
        }

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
