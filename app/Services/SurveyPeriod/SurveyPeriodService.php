<?php

declare(strict_types=1);

namespace App\Services\SurveyPeriod;

use App\DTO\SurveyPeriod\CreateSurveyPeriodDTO;
use App\DTO\SurveyPeriod\ListSurveyPeriodDTO;
use App\DTO\SurveyPeriod\UpdateSurveyPeriodDTO;
use App\Models\SurveyPeriod;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SurveyPeriodService
{
    public function getList(ListSurveyPeriodDTO $listSurveyPeriodDTO): Collection|LengthAwarePaginator|array
    {
        $query = SurveyPeriod::query()
            ->when(
                $listSurveyPeriodDTO->getQ(),
                fn ($q) => $q->where('title', 'like', '%' . $listSurveyPeriodDTO->getQ() . '%')
            )
            ->when($listSurveyPeriodDTO->getStatus(), fn ($q) => $q->where('status', $listSurveyPeriodDTO->getStatus()))
            ->when($listSurveyPeriodDTO->getType(), fn ($q) => $q->where('type', $listSurveyPeriodDTO->getType()))
            ->when($listSurveyPeriodDTO->getYear(), fn ($q) => $q->where('year', $listSurveyPeriodDTO->getYear()))
            ->when($listSurveyPeriodDTO->getStartDate(), fn ($q) => $q->where('start_date', '>=', $listSurveyPeriodDTO->getStartDate()))
            ->when($listSurveyPeriodDTO->getEndDate(), fn ($q) => $q->where('end_date', '<=', $listSurveyPeriodDTO->getEndDate()))
            ->when($listSurveyPeriodDTO->getFacultyId(), fn ($q) => $q->where('faculty_id', $listSurveyPeriodDTO->getFacultyId()))
            ->with('createdBy')
            ->orderBy($listSurveyPeriodDTO->getOrderBy(), $listSurveyPeriodDTO->getOrder()->value);

        return $listSurveyPeriodDTO->getPage() ? $query->paginate($listSurveyPeriodDTO->getLimit()) : $query->get();
    }

    public function create(CreateSurveyPeriodDTO $createSurveyPeriodDTO): SurveyPeriod
    {
        $surveyPeriod = SurveyPeriod::create(Arr::except($createSurveyPeriodDTO->toArray(), ['graduation_ceremony_ids']));
        $surveyPeriod->graduationCeremonies()->attach($createSurveyPeriodDTO->getGraduationCeremonyIds());
        $studentIds = $surveyPeriod->graduationCeremonies->pluck('students')->flatten()->pluck('id')->toArray();
        $surveyPeriod->students()->attach($studentIds);

        return $surveyPeriod;
    }

    public function update(UpdateSurveyPeriodDTO $updateSurveyPeriodDTO): SurveyPeriod
    {
        try {
            DB::beginTransaction();
            $surveyPeriod = SurveyPeriod::where('id', $updateSurveyPeriodDTO->getId())->first();
            $surveyPeriod->update(Arr::except($updateSurveyPeriodDTO->toArray(), ['graduation_ceremony_ids']));

            if ($updateSurveyPeriodDTO->getGraduationCeremonyIds()) {
                $currentGraduationCeremonyIds = $surveyPeriod->graduationCeremonies->pluck('id')->toArray();
                // Phần tử bị xóa
                $deletedGraduationIds = Arr::flatten(array_diff($currentGraduationCeremonyIds, $updateSurveyPeriodDTO->getGraduationCeremonyIds()));

                // Phần tử được thêm mới
                $addedGraduationIds = Arr::flatten(array_diff($updateSurveyPeriodDTO->getGraduationCeremonyIds(), $currentGraduationCeremonyIds));

                // Xóa các phần tử bị xóa
                if (count($deletedGraduationIds) > 0) {
                    $surveyPeriod->graduationCeremonies()->detach($deletedGraduationIds);
                    $studentIds = $surveyPeriod->graduationCeremonies->whereIn('id', $deletedGraduationIds)
                        ->pluck('students')->flatten()->pluck('id')->toArray();
                    $surveyPeriod->students()->detach($studentIds);
                }

                if (count($addedGraduationIds) > 0) {
                    $surveyPeriod->graduationCeremonies()->syncWithoutDetaching($addedGraduationIds);
                    $studentAdds = $surveyPeriod->graduationCeremonies()->with('students')->whereIn('graduation_ceremonies.id', $addedGraduationIds)->get();
                    $studentIds = $studentAdds->pluck('students')->flatten()->pluck('id')->toArray();
                    $surveyPeriod->students()->syncWithoutDetaching($studentIds);
                }
            }
            DB::commit();

            return $surveyPeriod;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function show(mixed $id): SurveyPeriod
    {
        $surveyPeriod = $id instanceof SurveyPeriod ? $id : SurveyPeriod::where('id', $id)->first();
        $surveyPeriod->load('graduationCeremonies', 'students.info', 'students.currentClass');

        return $surveyPeriod;
    }

    public function delete(mixed $id): bool
    {
        $surveyPeriod = SurveyPeriod::where('id', $id)->first();
        $surveyPeriod->graduationCeremonies()->detach();
        $surveyPeriod->students()->detach();

        return $surveyPeriod->delete();
    }
}