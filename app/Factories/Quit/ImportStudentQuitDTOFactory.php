<?php

declare(strict_types=1);

namespace App\Factories\Quit;

use App\DTO\Quit\ImportStudentQuitDTO;
use App\Http\Requests\Admin\StudentQuit\ImportStudentQuitRequest;

class ImportStudentQuitDTOFactory
{
    public static function make(ImportStudentQuitRequest $request): ImportStudentQuitDTO
    {
        $command = new ImportStudentQuitDTO();
        $command->setQuitId($request->get('quit_id'));
        $command->setFile($request->file('file'));
        return $command;
    }
}
