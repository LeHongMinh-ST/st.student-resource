<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\CreateFamilyStudentDTO;
use App\DTO\Student\CreateStudentCourseByFileDTO;
use App\DTO\Student\CreateStudentInfoByFileImportDTO;
use App\Enums\FamilyRelationship;
use App\Enums\Gender;
use App\Enums\StudentRole;
use App\Enums\TrainingType;
use App\Supports\AvatarHelper;
use App\Supports\Constants;
use Illuminate\Support\Arr;

class CreateStudentByFileDTOFactory
{
    /**
     * Create a CreateStudentCourseByFileCommand instance from array data.
     *
     * @return CreateStudentCourseByFileDTO The command object representing the student.
     */
    public static function make(array $data): CreateStudentCourseByFileDTO
    {
        // Create a new command instance
        $command = new CreateStudentCourseByFileDTO();

        // Set properties of the command object from the request data
        $command->setCode($data['code']);
        $command->setFacultyId($data['faculty_id']);

        // set last name and first name
        $fullName = explode(' ', $data['full_name']);
        $command->setFirstName(array_pop($fullName));
        $command->setLastName(implode(' ', $fullName));
        $command->setStudentRole(StudentRole::Basic);
        $command->setAdmissionYearId($data['admission_year_id']);

        // Handle thumbnail image upload or generate an avatar if no image is uploaded
        $thumbnailFileName = uniqid() . $command->getCode();
        $path = AvatarHelper::createAvatar(
            $command->getFullName(),
            Constants::PATH_THUMBNAIL_STUDENT,
            $thumbnailFileName
        );

        // set dto student info
        $commandStudentInfo = new CreateStudentInfoByFileImportDTO();
        $commandStudentInfo->setPersonEmail(Arr::get($data, 'person_email'));
        $commandStudentInfo->setGender(Gender::mapValue(Arr::get($data, 'gender') ?? ''));
        $commandStudentInfo->setDob(Arr::get($data, 'dob'));
        $commandStudentInfo->setAddress(Arr::get($data, 'address'));
        $commandStudentInfo->setTrainingType(TrainingType::FormalUniversity);
        $commandStudentInfo->setPhone(Arr::get($data, 'phone_number'));
        $commandStudentInfo->setEthnic(Arr::get($data, 'ethnic'));
        $commandStudentInfo->setThumbnail($path);

        if (count($commandStudentInfo->toArray())) {
            $command->setStudentInfoDTO($commandStudentInfo);
        }


        // set family info
        $families = [];
        if (Arr::get($data, 'father_name') || Arr::get($data, 'father_phone')) {
            $commandFamilyInfo = new CreateFamilyStudentDTO();
            $commandFamilyInfo->setFullName(Arr::get($data, 'father_name'));
            $commandFamilyInfo->setPhone(Arr::get($data, 'father_phone'));
            $commandFamilyInfo->setRelationship(FamilyRelationship::Father);
            $families[] = $commandFamilyInfo;
        }

        if (Arr::get($data, 'mother_name') || Arr::get($data, 'mother_phone')) {
            $commandFamilyInfo = new CreateFamilyStudentDTO();
            $commandFamilyInfo->setFullName(Arr::get($data, 'mother_name'));
            $commandFamilyInfo->setPhone(Arr::get($data, 'mother_phone'));
            $commandFamilyInfo->setRelationship(FamilyRelationship::Mother);
            $families[] = $commandFamilyInfo;
        }

        if (count($families)) {
            $command->setFamily($families);
        }

        return $command;
    }
}
