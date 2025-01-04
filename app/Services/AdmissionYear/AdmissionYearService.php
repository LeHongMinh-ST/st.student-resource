<?php

declare(strict_types=1);

namespace App\Services\AdmissionYear;

use App\DTO\AdmissionYear\ListAdmissionYearDTO;
use App\Enums\Status;
use App\Enums\StudentStatus;
use App\Models\AdmissionYear;
use App\Models\TrainingIndustry;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AdmissionYearService
{
    public function getList(ListAdmissionYearDTO $admissionYearDTO): Collection|LengthAwarePaginator|array
    {
        $query = AdmissionYear::query()
            ->with(['students' => function ($q): void {
                if (auth('api')->check()) {
                    $q->where('faculty_id', auth('api')->user()->faculty_id);
                }

            }], ['generalClasses' => function ($q): void {
                if (auth('api')->check()) {
                    $q->where('faculty_id', auth('api')->user()->faculty_id);
                }
            }])
            ->withCount([
                'students' => function ($query): void {
                    $query->where('faculty_id', auth('api')->user()->faculty_id);
                },
                'students as currently_studying_count' => function ($query): void {
                    $query->where('faculty_id', auth('api')->user()->faculty_id)
                        ->where('status', StudentStatus::CurrentlyStudying);
                },
                'generalClasses' => function ($query): void {
                    $query
                        ->where('status', Status::Enable)
                        ->where('faculty_id', auth('api')->user()->faculty_id);
                }
            ])
            ->orderBy($admissionYearDTO->getOrderBy(), $admissionYearDTO->getOrder()->value);
        return $admissionYearDTO->getPage() ? $query->paginate($admissionYearDTO->getLimit()) : $query->get();
    }


    public function getTrainingIndustryClassByAdmissionId(AdmissionYear $admissionYear)
    {
        $trainingIndustryIds = $admissionYear->generalClasses()->pluck('training_industry_id');
        $trainingIndustries = TrainingIndustry::query()
            ->whereIn('id', $trainingIndustryIds)
            ->withCount(['generalClassesEnable as generalClasses'])
            ->get();
        return $trainingIndustries;
    }

}
