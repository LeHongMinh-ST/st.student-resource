<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\CreateRequestUpdateFamilyStudentDTO;
use App\Enums\FamilyRelationship;

class CreateRequestUpdateFamilyStudentDTOFactory
{
    public static function make(array $data): CreateRequestUpdateFamilyStudentDTO
    {
        $command = new CreateRequestUpdateFamilyStudentDTO();
        $command->setRelationship(@$data['relationship'] ? FamilyRelationship::from($data['relationship']) : '');
        $command->setFullName($data['full_name'] ?? '');
        $command->setJob($data['job'] ?? '');
        $command->setPhone($data['phone'] ?? '');
        return $command;
    }
}
