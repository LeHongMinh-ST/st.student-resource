<?php

declare(strict_types=1);

namespace App\Services\Faculty;

use App\DTO\Faculty\CreateFacultyDTO;
use App\DTO\Faculty\ListFacultyDTO;
use App\Factories\User\CreateUserFacultyDTOFactory;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class FacultyService
{
    public function getList(ListFacultyDTO $listFacultyDTO): Collection|LengthAwarePaginator|array
    {
        $query = Faculty::query()
            ->when($listFacultyDTO->getSearch(), fn ($q) => $q->where('name', 'like', $listFacultyDTO->getSearch()))
            ->orderBy($listFacultyDTO->getOrderBy(), $listFacultyDTO->getOrder()->value);

        return $listFacultyDTO->getPage() ? $query->paginate($listFacultyDTO->getLimit()) : $query->get();
    }

    public function create(CreateFacultyDTO $createFacultyDTO): Faculty
    {
        return DB::transaction(function () use ($createFacultyDTO): Faculty {
            $faculty = Faculty::create($createFacultyDTO->toArray());
            $userDTO = CreateUserFacultyDTOFactory::make($faculty, $createFacultyDTO->getEmailUser());
            User::create($userDTO->toArray());

            return $faculty;
        });
    }
}
