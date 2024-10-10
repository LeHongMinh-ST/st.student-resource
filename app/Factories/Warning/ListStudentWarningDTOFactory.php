<?php

declare(strict_types=1);

namespace App\Factories\Warning;

use App\DTO\Warning\ListStudentWarningDTO;
use App\Http\Requests\Admin\StudentWarning\ListStudentWarningRequest;
use App\Supports\MakeDataHelper;

class ListStudentWarningDTOFactory
{
    public static function make(ListStudentWarningRequest $request): ListStudentWarningDTO
    {
        $command = new ListStudentWarningDTO();
        $command = MakeDataHelper::makeListData($request, $command);
        $command->setSemesterId($request->get('semester_id'));
        return $command;
    }
}
