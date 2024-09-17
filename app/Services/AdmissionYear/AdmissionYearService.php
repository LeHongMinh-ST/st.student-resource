<?php

declare(strict_types=1);

namespace App\Services\AdmissionYear;

use App\DTO\AdmissionYear\ListAdmissionYearDTO;
use App\Models\AdmissionYear;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AdmissionYearService
{
    public function getList(ListAdmissionYearDTO $admissionYearDTO): Collection|LengthAwarePaginator|array
    {
        $query = AdmissionYear::query()
            ->with('students', function ($q): void {
                if (auth('api')->check()) {
                    $q->where('faculty_id', auth('api')->user()->faculty_id);
                }
            })
            ->orderBy($admissionYearDTO->getOrderBy(), $admissionYearDTO->getOrder()->value);
        return $admissionYearDTO->getPage() ? $query->paginate($admissionYearDTO->getLimit()) : $query->get();
    }


}
