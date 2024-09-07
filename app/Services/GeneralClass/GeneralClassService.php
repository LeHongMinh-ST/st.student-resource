<?php

declare(strict_types=1);

namespace App\Services\GeneralClass;

use App\DTO\GeneralClass\CreateGeneralClassDTO;
use App\DTO\GeneralClass\ListGeneralClassDTO;
use App\DTO\GeneralClass\UpdateGeneralClassDTO;
use App\Models\GeneralClass;

class GeneralClassService
{
    public function getList(ListGeneralClassDTO $listFacultyDTO): \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator|array
    {
        $query = GeneralClass::query()
            ->when($listFacultyDTO->getTeacherId(), fn ($q) => $q->where('teacher_id', $listFacultyDTO->getTeacherId()))
            ->when($listFacultyDTO->getQ(), fn ($q) => $q->where('name', 'like', $listFacultyDTO->getQ()))
            ->orderBy($listFacultyDTO->getOrderBy(), $listFacultyDTO->getOrder()->value);

        return $listFacultyDTO->getPage() ? $query->paginate($listFacultyDTO->getLimit()) : $query->get();
    }

    public function create(CreateGeneralClassDTO $createFacultyDTO): GeneralClass
    {
        return GeneralClass::create($createFacultyDTO->toArray());
    }

    public function update(UpdateGeneralClassDTO $createFacultyDTO): GeneralClass
    {
        $class = GeneralClass::where('id', $createFacultyDTO->getId())->first();
        $class->update($createFacultyDTO->toArray());
        return $class;
    }

    public function delete(mixed $id): bool
    {
        $generalClass = $this->getGeneralClassById($id);

        return $generalClass->delete();
    }

    public function getGeneralClassById(mixed $id): GeneralClass
    {
        return $id instanceof GeneralClass ? $id : GeneralClass::find($id);
    }
}
