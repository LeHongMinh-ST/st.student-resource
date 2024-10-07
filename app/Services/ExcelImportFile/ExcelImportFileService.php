<?php

declare(strict_types=1);

namespace App\Services\ExcelImportFile;

use App\DTO\AdmissionYear\ListStudentImportDTO;
use App\Enums\ExcelImportType;
use App\Models\ExcelImportFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ExcelImportFileService
{
    public function getListStudentFileImports(ListStudentImportDTO $listStudentImportDTO): Collection|LengthAwarePaginator|array
    {
        $auth = auth('api')->user();
        $query = ExcelImportFile::query()
            ->where('faculty_id', $auth?->faculty_id ?? null)
            ->where('type', ExcelImportType::Course)
            ->when($listStudentImportDTO->getAdmissionYearId(), fn ($query) => $query->where('type_id', $listStudentImportDTO->getAdmissionYearId()))
            ->with(['user', 'excelImportFileErrors'])
            ->orderBy($listStudentImportDTO->getOrderBy(), $listStudentImportDTO->getOrder()->value);

        return $listStudentImportDTO->getPage() ? $query->paginate($listStudentImportDTO->getLimit()) : $query->get();
    }
}
