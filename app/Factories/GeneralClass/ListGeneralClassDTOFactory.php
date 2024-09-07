<?php

declare(strict_types=1);

namespace App\Factories\GeneralClass;

use App\DTO\GeneralClass\ListGeneralClassDTO;
use App\Enums\SortOrder;
use App\Http\Requests\GeneralClass\ListGeneralClassRequest;

class ListGeneralClassDTOFactory
{
    public static function make(ListGeneralClassRequest $request): ListGeneralClassDTO
    {
        // Create a new ListFacultyCommand object
        $command = new ListGeneralClassDTO();

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
