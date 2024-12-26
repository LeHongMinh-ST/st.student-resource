<?php

declare(strict_types=1);

namespace App\Factories\Faculty;

use App\DTO\Faculty\ListFacultyDTO;
use App\Http\Requests\SystemAdmin\Faculty\ListFacultyRequest;
use App\Supports\MakeDataHelper;

class ListFacultyDTOFactory
{
    public static function make(ListFacultyRequest $request): ListFacultyDTO
    {
        // Create a new ListFacultyCommand object
        $command = new ListFacultyDTO();

        // Set command properties based on the request parameters, if they exist
        return MakeDataHelper::makeListData($request, $command);
    }
}
