<?php

declare(strict_types=1);

namespace App\Services\Department;

use App\DTO\Department\CreateDepartmentDTO;
use App\DTO\Department\ListDepartmentDTO;
use App\DTO\Department\UpdateDepartmentDTO;
use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class DepartmentService
{
    public function getList(ListDepartmentDTO $listDepartmentDTO): Collection|LengthAwarePaginator|array
    {
        $query = Department::query()
            ->when(
                $listDepartmentDTO->getQ(),
                fn ($q) => $q->where('code', 'like', '%' . $listDepartmentDTO->getQ() . '%')->orWhere('name', 'like', '%' . $listDepartmentDTO->getQ() . '%')
            )
            ->when($listDepartmentDTO->getStatus(), fn ($q) => $q->where('status', $listDepartmentDTO->getStatus()))
            ->when($listDepartmentDTO->getFacultyId(), fn ($q) => $q->where('faculty_id', $listDepartmentDTO->getFacultyId()))
            ->with(['faculty'])
            ->orderBy($listDepartmentDTO->getOrderBy(), $listDepartmentDTO->getOrder()->value);

        return $listDepartmentDTO->getPage() ? $query->paginate($listDepartmentDTO->getLimit()) : $query->get();
    }

    public function create(CreateDepartmentDTO $createDepartmentDTO): Department
    {
        return Department::create($createDepartmentDTO->toArray());
    }

    public function update(UpdateDepartmentDTO $updateDepartmentDTO): Department
    {
        $department = Department::where('id', $updateDepartmentDTO->getId())->first();
        $department->update($updateDepartmentDTO->toArray());

        return $department;
    }

    public function delete(mixed $id): bool
    {
        $department = $this->getDepartmentById($id);
        if ($department->users->count()) {
            throw ValidationException::withMessages(
                ['message' => 'Department is being used by users']
            );
        }

        return $department->delete();
    }

    public function getDepartmentById(mixed $id): Department
    {
        return $id instanceof Department ? $id : Department::find($id);
    }
}
