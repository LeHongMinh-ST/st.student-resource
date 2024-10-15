<?php

declare(strict_types=1);

namespace App\Factories\GeneralClass;

use App\DTO\GeneralClass\ListGeneralClassDTO;
use App\Http\Requests\Admin\GeneralClass\ListGeneralClassRequest;
use App\Supports\MakeDataHelper;

class ListGeneralClassDTOFactory
{
    public static function make(ListGeneralClassRequest $request): ListGeneralClassDTO
    {
        // Create a new ListFacultyCommand object
        $command = new ListGeneralClassDTO();
        $command->setStatus($request->status);
        $command->setFacultyId(auth()->user()->faculty_id);

        // Set command properties based on the request parameters, if they exist
        return MakeDataHelper::makeListData($request, $command);
    }
}
