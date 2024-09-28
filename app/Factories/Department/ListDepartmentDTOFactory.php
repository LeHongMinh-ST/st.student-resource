<?php

declare(strict_types=1);

namespace App\Factories\Department;

use App\DTO\Department\ListDepartmentDTO;
use App\Http\Requests\Admin\Department\ListDepartmentRequest;
use App\Supports\MakeDataHelper;

class ListDepartmentDTOFactory
{
    public static function make(ListDepartmentRequest $request): ListDepartmentDTO
    {
        // Create a new ListDepartmentDTO object
        $command = new ListDepartmentDTO();

        // Set command properties based on the request parameters, if they exist
        return MakeDataHelper::makeListData($request, $command);
    }
}
