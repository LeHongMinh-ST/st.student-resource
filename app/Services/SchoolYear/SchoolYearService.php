<?php

declare(strict_types=1);

namespace App\Services\SchoolYear;

use App\DTO\SchoolYear\ListSchoolYearDTO;
use App\Models\SchoolYear;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SchoolYearService
{
    public function getList(ListSchoolYearDTO $schoolYearDTO): Collection|LengthAwarePaginator|array
    {
        $query = SchoolYear::query()
            ->orderBy($schoolYearDTO->getOrderBy(), $schoolYearDTO->getOrder()->value);

        return $schoolYearDTO->getPage() ? $query->paginate($schoolYearDTO->getLimit()) : $query->get();
    }
}
