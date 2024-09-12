<?php

declare(strict_types=1);

namespace App\Factories\User;

use App\DTO\User\ListUserDTO;
use App\Enums\SortOrder;
use App\Http\Requests\Admin\User\ListUserRequest;

class ListUserDTOFactory
{
    public static function make(ListUserRequest $request): ListUserDTO
    {
        // Create a new ListFacultyCommand object
        $command = new ListUserDTO();

        // Set command properties based on the request parameters, if they exist
        if ($request->has('limit')) {
            $command->setLimit($request->get('limit'));
        }
        if ($request->has('page')) {
            $command->setPage((int) $request->get('page'));
        }
        if ($request->has('orderBy')) {
            $command->setOrderBy($request->get('orderBy'));
        }
        if ($request->has('order')) {
            $command->setOrder(SortOrder::from($request->get('order')));
        }
        if ($request->has('department_id')) {
            $command->setDepartmentId((int) $request->get('department_id'));
        }
        if ($request->has('q')) {
            $command->setQ($request->get('q'));
        }
        if ($request->has('status')) {
            $command->setStatus($request->get('status'));
        }

        $command->setFacultyId(auth()->user()->faculty_id);

        return $command;
    }
}
