<?php

declare(strict_types=1);

namespace App\Services\Citi;

use App\Models\Cities;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CitiService
{
    public function getList(array $filter): Collection|LengthAwarePaginator|array
    {
        return Cities::query()->get();
    }
}
