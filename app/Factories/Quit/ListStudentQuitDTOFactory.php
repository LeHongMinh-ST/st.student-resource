<?php

declare(strict_types=1);

namespace App\Factories\Quit;

use App\DTO\Quit\ListStudentQuitDTO;
use App\Http\Requests\Admin\StudentQuit\ListStudentQuitRequest;
use App\Supports\MakeDataHelper;

class ListStudentQuitDTOFactory
{
    public static function make(ListStudentQuitRequest $request): ListStudentQuitDTO
    {
        $command = new ListStudentQuitDTO();
        $command = MakeDataHelper::makeListData($request, $command);
        $command->setSemesterId($request->get('semester_id'));
        return $command;
    }
}
