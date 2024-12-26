<?php

declare(strict_types=1);

namespace App\Factories\Warning;

use App\DTO\Warning\ImportStudentWarningDTO;
use App\Http\Requests\Admin\StudentWarning\ImportStudentWarningRequest;

class ImportStudentWarningDTOFactory
{
    public static function make(ImportStudentWarningRequest $request): ImportStudentWarningDTO
    {
        $command = new ImportStudentWarningDTO();
        $command->setWarningId($request->get('warning_id'));
        $command->setFile($request->file('file'));
        return $command;
    }
}
