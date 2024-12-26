<?php

declare(strict_types=1);

namespace App\Supports;

use App\DTO\BaseListDTO;
use App\Enums\SortOrder;
use App\Http\Requests\ListRequest;

class MakeDataHelper
{
    /**
     * @template T of BaseListDTO
     * @param ListRequest $request
     * @param T $baseDTO
     * @return T
     */
    public static function makeListData(ListRequest $request, BaseListDTO $baseDTO): BaseListDTO
    {
        if ($request->has('limit')) {
            $baseDTO->setLimit((int)$request->get('limit'));
        }
        if ($request->has('page')) {
            $baseDTO->setPage((int) $request->get('page'));
        }
        if ($request->has('orderBy')) {
            $baseDTO->setOrderBy($request->get('orderBy'));
        }
        if ($request->has('order')) {
            $baseDTO->setOrder(SortOrder::from($request->get('order')));
        }
        return $baseDTO;
    }
}
