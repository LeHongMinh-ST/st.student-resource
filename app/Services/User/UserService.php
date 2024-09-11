<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\ListUserDTO;
use App\DTO\User\UpdateUserDTO;
use App\Models\User;

class UserService
{
    public function getList(ListUserDTO $listUserDTO): \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator|array
    {
        $query = User::query()
            ->when($listUserDTO->getDepartmentId(), fn ($q) => $q->where('department_id', $listUserDTO->getDepartmentId()))
            ->when($listUserDTO->getQ(), fn ($q) => $q->where('name', 'like', $listUserDTO->getQ()))
            ->orderBy($listUserDTO->getOrderBy(), $listUserDTO->getOrder()->value);

        return $listUserDTO->getPage() ? $query->paginate($listUserDTO->getLimit()) : $query->get();
    }

    public function create(CreateUserDTO $createUserDTO): User
    {
        return User::create($createUserDTO->toArray());
    }

    public function update(UpdateUserDTO $createUserDTO): User
    {
        $class = User::where('id', $createUserDTO->getId())->first();
        $class->update($createUserDTO->toArray());
        return $class;
    }

    public function delete(mixed $id): bool
    {
        $generalClass = $this->getUserById($id);

        return $generalClass->delete();
    }

    public function getUserById(mixed $id): User
    {
        return $id instanceof User ? $id : User::find($id);
    }
}
