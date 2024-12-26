<?php

declare(strict_types=1);

namespace App\Factories\RequestUpdateStudent;

use App\DTO\Student\UpdateRequestUpdateFamilyStudentDTO;
use App\Enums\FamilyRelationship;

class UpdateRequestUpdateFamilyStudentDTOFactory
{
    public static function make(array $data, int|string $studentInfoId): UpdateRequestUpdateFamilyStudentDTO
    {
        $command = new UpdateRequestUpdateFamilyStudentDTO();
        $command->setStudentInfoUpdateId($studentInfoId);
        $command->setRelationship(@$data['relationship'] ? FamilyRelationship::from($data['relationship']) : '');
        $command->setFullName($data['full_name'] ?? '');
        $command->setJob($data['job'] ?? '');
        $command->setPhone($data['phone'] ?? '');
        return $command;
    }
}
