<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\ChangeStatusRequestUpdateStudentDTO;
use App\Enums\StudentInfoUpdateStatus;
use App\Http\Requests\Admin\Student\ChangeStatusRequestUpdateStudentRequest as AdminChangeStatusRequestUpdateStudentRequest;
use App\Http\Requests\Student\RequestUpdateInfo\ChangeStatusRequestUpdateStudentRequest;
use InvalidArgumentException;


class ChangeStatusRequestUpdateStudentDTOFactory
{
    public static function make(
        ChangeStatusRequestUpdateStudentRequest|AdminChangeStatusRequestUpdateStudentRequest $request,
        int $id
    ): ChangeStatusRequestUpdateStudentDTO {
        try {
            return new ChangeStatusRequestUpdateStudentDTO(
                $id,
                StudentInfoUpdateStatus::from($request->get('status')),
                $request->get('reject_note')
            );
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }
}
