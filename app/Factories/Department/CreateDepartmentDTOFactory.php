<?php

declare(strict_types=1);

namespace App\Factories\Department;

use App\DTO\Department\CreateDepartmentDTO;
use App\Enums\Status;
use App\Http\Requests\Admin\Department\StoreDepartmentRequest;

class CreateDepartmentDTOFactory
{
    public static function make(StoreDepartmentRequest $request): CreateDepartmentDTO
    {
        $dto = new CreateDepartmentDTO();
        $dto->setName($request->get('name'));
        $dto->setCode($request->get('code'));
        if ($request->get('status')) {
            $dto->setStatus(Status::from($request->get('status')));
        }
        return $dto;
    }
}
