<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\CreateStudentCourseByFileDTO;
use App\Enums\Gender;
use App\Enums\StudentRole;
use App\Supports\AvatarHelper;
use App\Supports\Constants;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

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
        $command->setLastName(array_pop($fullName));
        $command->setFirstName(implode(' ', $fullName));

        $command->setGender(Gender::mapValue(Arr::get($data, 'gender') ?? ''));
        $command->setStudentRole(StudentRole::Basic);
        $command->setSchoolYear($data['school_year']);
        $command->setPhoneNumber(Arr::get($data, 'phone_number'));
        $command->setDob(Arr::get($data, 'dob') ? Carbon::createFromFormat('d/m/Y', $data['dob'])->format('Y-m-d') : null);

        // Handle thumbnail image upload or generate an avatar if no image is uploaded
        $thumbnailFileName = uniqid() . $command->getCode();
        $path = AvatarHelper::createAvatar(
            $command->getFullName(),
            Constants::PATH_THUMBNAIL_STUDENT,
            $thumbnailFileName
        );

        // Set the thumbnail path in the command object
        $command->setThumbnail($path);

        return $command;
    }
}
