<?php

declare(strict_types=1);

namespace App\Services\TrainingIndustry;

use App\DTO\TrainingIndustry\CreateTrainingIndustryDTO;
use App\DTO\TrainingIndustry\ListTrainingIndustryDTO;
use App\DTO\TrainingIndustry\UpdateTrainingIndustryDTO;
use App\Enums\Status;
use App\Models\TrainingIndustry;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class TrainingIndustryService
{
    public function getList(ListTrainingIndustryDTO $listTrainingIndustryDTO): Collection|LengthAwarePaginator|array
    {
        $query = TrainingIndustry::query()
            ->when(
                $listTrainingIndustryDTO->getQ(),
                fn ($q) => $q->where('code', 'like', '%' . $listTrainingIndustryDTO->getQ() . '%')->orWhere('name', 'like', '%' . $listTrainingIndustryDTO->getQ() . '%')
            )
            ->when($listTrainingIndustryDTO->getStatus(), fn ($q) => $q->where('status', $listTrainingIndustryDTO->getStatus()))
            ->when($listTrainingIndustryDTO->getFacultyId(), fn ($q) => $q->where('faculty_id', $listTrainingIndustryDTO->getFacultyId()))
            ->with(['faculty'])
            ->orderBy($listTrainingIndustryDTO->getOrderBy(), $listTrainingIndustryDTO->getOrder()->value);

        return $listTrainingIndustryDTO->getPage() ? $query->paginate($listTrainingIndustryDTO->getLimit()) : $query->get();
    }

    public function create(CreateTrainingIndustryDTO $createTrainingIndustryDTO): TrainingIndustry
    {
        return TrainingIndustry::create($createTrainingIndustryDTO->toArray());
    }

    public function update(UpdateTrainingIndustryDTO $updateTrainingIndustryDTO): TrainingIndustry
    {
        $trainingIndustry = TrainingIndustry::where('id', $updateTrainingIndustryDTO->getId())->first();
        $trainingIndustry->update($updateTrainingIndustryDTO->toArray());

        return $trainingIndustry;
    }

    public function delete(mixed $id): bool
    {
        $trainingIndustry = TrainingIndustry::where('id', $id)->first();
        if (Status::Enable === $trainingIndustry->status) {
            throw ValidationException::withMessages(['message' => 'không thể xóa ngành đào tạo đang hoạt động']);
        }

        return $trainingIndustry->delete();
    }
}
