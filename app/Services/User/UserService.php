<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\ListUserDTO;
use App\DTO\User\UpdateUserDTO;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getList(ListUserDTO $listUserDTO): Collection|LengthAwarePaginator|array
    {
        $auth = auth('api')->user();

        $query = User::query()
            ->when($listUserDTO->getDepartmentId(), fn ($q) => $q->where('department_id', $listUserDTO->getDepartmentId()))
            ->when($listUserDTO->getUserRole(), fn ($q) => $q->where('role', $listUserDTO->getUserRole()))
            ->when(
                $listUserDTO->getQ(),
                fn ($q) => $q
                    ->where(DB::raw("CONCAT(last_name, ' ', first_name)"), 'like', '%' . $listUserDTO->getQ() . '%')
                    ->orWhere('email', 'like', '%' . $listUserDTO->getQ() . '%')
                    ->orWhere('user_name', 'like', '%' . $listUserDTO->getQ() . '%')
            )
            ->where('faculty_id', $auth?->faculty_id)
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

    public function updatePassword($id, $password): void
    {
        User::where('id', $id)->update(['password' => $password]);
    }
}
