<?php

declare(strict_types=1);

namespace App\Factories\Department;

use App\DTO\Department\ListDepartmentDTO;
use App\Enums\Status;
use App\Http\Requests\Admin\Department\ListDepartmentRequest;
use App\Supports\MakeDataHelper;

class ListDepartmentDTOFactory
{
    public static function make(ListDepartmentRequest $request): ListDepartmentDTO
    {
        // Create a new ListDepartmentDTO object
        $command = new ListDepartmentDTO();

        if ($request->has('q')) {
            $command->setQ($request->q);
        }

        if ($request->has('status')) {
            $command->setStatus(Status::from($request->status));
        }

        // Set command properties based on the request parameters, if they exist
        return MakeDataHelper::makeListData($request, $command);
    }
}
