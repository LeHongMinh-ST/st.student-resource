<?php

declare(strict_types=1);

namespace App\Factories\User;

use App\DTO\User\ListUserDTO;
use App\Enums\UserRole;
use App\Http\Requests\Admin\User\ListUserRequest;
use App\Supports\MakeDataHelper;

class ListUserDTOFactory
{
    public static function make(ListUserRequest $request): ListUserDTO
    {
        // Create a new ListFacultyCommand object
        $command = new ListUserDTO();

        // Set command properties based on the request parameters, if they exist
        $command = MakeDataHelper::makeListData($request, $command);

        if ($request->has('department_id')) {
            $command->setDepartmentId((int) $request->get('department_id'));
        }

        if ($request->has('q')) {
            $command->setQ($request->get('q'));
        }

        if ($request->has('status')) {
            $command->setStatus($request->get('status'));
        }

        if ($request->has('role')) {
            $command->setUserRole(UserRole::from($request->get('role')));
        }

        $command->setFacultyId(auth()->user()->faculty_id);

        return $command;
    }
}
