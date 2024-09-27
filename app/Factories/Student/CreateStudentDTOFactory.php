<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\CreateStudentDTO;
use App\Enums\Gender;
use App\Enums\StudentRole;
use App\Http\Requests\Admin\Student\CreateStudentRequest;
use App\Supports\AvatarHelper;
use App\Supports\Constants;
use App\Supports\ImageHelper;
use App\Supports\PasswordHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CreateStudentDTOFactory
{
    /**
     * Create a CreateStudentCommand instance from a CreateStudentRequest.
     *
     * @param  CreateStudentRequest  $request  The request containing student data.
     * @return CreateStudentDTO The command object representing the student.
     */
    public static function make(CreateStudentRequest $request): CreateStudentDTO
    {
        // Create a new command instance
        $studentDTO = new CreateStudentDTO();

        // Set properties of the command object from the request data
        $studentDTO->setCode($request->get('code'));
        $studentDTO->setLastName($request->get('last_name'));
        $studentDTO->setFirstName($request->get('first_name')); // Corrected "last_name" to "first_name"
        $studentDTO->setGender(Gender::from($request->get('gender')));
        $studentDTO->setStudentRole(StudentRole::from($request->get('role')));
        $studentDTO->setSchoolYear($request->get('school_year'));
        $studentDTO->setPassword($request->get('password') ?? PasswordHelper::makePassword());

        // Handle thumbnail image upload or generate an avatar if no image is uploaded
        if ($request->hasFile('thumbnail') && $request->file('thumbnail') instanceof UploadedFile) {
            $path = ImageHelper::uploadImage(
                $request->file('thumbnail'),
                Constants::PATH_THUMBNAIL_STUDENT,
            );
        } else {
            $thumbnailFileName = uniqid() . $request->get('code') . Str::random(3);
            $path = AvatarHelper::createAvatar(
                $request->get('code'),
                Constants::PATH_THUMBNAIL_STUDENT,
                $thumbnailFileName
            );
        }

        // Set the thumbnail path in the command object
        $studentDTO->setThumbnail($path);

        return $studentDTO;
    }
}
