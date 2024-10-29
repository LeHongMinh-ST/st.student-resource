<?php

declare(strict_types=1);

namespace App\Factories\ExcelImportFile;

use App\DTO\ExcelImportFile\ListExcelImportFileDTO;
use App\Enums\AuthApiSection;
use App\Enums\ExcelImportType;
use App\Http\Requests\Admin\ExcelImportFile\ListExcelImportFileRequest;
use App\Supports\MakeDataHelper;

class ListExcelImportFileDTOFactory
{
    public static function make(ListExcelImportFileRequest $request): ListExcelImportFileDTO
    {
        $command = new ListExcelImportFileDTO();

        if ($request->has('type')) {
            $command->setType(ExcelImportType::from($request->get('type')));
        }

        if ($request->has('entity_id')) {
            $command->setTypeId((int) $request->get('entity_id'));
        }

        $command->setFacultyId((int) auth(AuthApiSection::Admin->value)->user()?->faculty_id);

        return MakeDataHelper::makeListData($request, $command);
    }
}
