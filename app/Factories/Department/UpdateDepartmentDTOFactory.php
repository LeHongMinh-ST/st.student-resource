<?php

declare(strict_types=1);

namespace App\Factories\Department;

use App\DTO\Department\UpdateDepartmentDTO;
use App\Enums\Status;
use App\Http\Requests\Admin\Department\UpdateDepartmentRequest;
use App\Models\Department;

class UpdateDepartmentDTOFactory
{
    public static function make(UpdateDepartmentRequest $request, Department $department): UpdateDepartmentDTO
    {
        // Create a new UpdateDepartmentDTO object
        $command = new UpdateDepartmentDTO();

        $command->setId($department->id);
        $command->setName($request->get('name'));
        $command->setCode($request->get('code'));
        if ($request->get('status')) {
            $command->setStatus(Status::from($request->get('status')));
        }

        return $command;
    }
}
