<?php

declare(strict_types=1);

namespace App\Factories\Faculty;

use App\DTO\Faculty\ListFacultyDTO;
use App\Enums\SortOrder;
use App\Http\Requests\Faculty\ListFacultyRequest;

class ListFacultyDTOFactory
{
    public static function make(ListFacultyRequest $request): ListFacultyDTO
    {
        // Create a new ListFacultyCommand object
        $command = new ListFacultyDTO();

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
