<?php

declare(strict_types=1);

namespace App\Factories\Faculty;

use App\DTO\Faculty\CreateFacultyDTO;
use App\Http\Requests\Faculty\StoreFacultyRequest;

class CreateFacultyDTOFactory
{
    public static function make(StoreFacultyRequest $request): CreateFacultyDTO
    {
        $dto = new CreateFacultyDTO();
        $dto->setName($request->get('name'));
        $dto->setCode($request->get('code'));
        $dto->setEmailUser($request->get('email'));
        return $dto;
    }
}
