<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\ListStudentDTO;
use App\Enums\SortOrder;
use App\Http\Requests\Admin\Student\ListStudentRequest;

class ListStudentDTOFactory
{
    public static function make(ListStudentRequest $request): ListStudentDTO
    {
        // Create a new ListStudentDTO object
        $command = new ListStudentDTO();

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

        return $command;
    }
}
