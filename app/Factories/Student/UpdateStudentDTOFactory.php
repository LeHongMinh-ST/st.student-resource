<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\UpdateStudentDTO;
use App\DTO\Student\UpdateStudentInfoDTO;
use App\Enums\Gender;
use App\Enums\TrainingType;
use App\Http\Requests\Admin\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Supports\Constants;
use App\Supports\ImageHelper;
use Illuminate\Http\UploadedFile;

class UpdateStudentDTOFactory
{
    /**
     * Create a UpdateStudentDTO instance from a UpdateStudentRequest.
     *
     * @param  UpdateStudentRequest  $request  The request containing student data.
     * @return UpdateStudentDTO The command object representing the student.
     */
    public static function make(UpdateStudentRequest $request, Student $student): UpdateStudentDTO
    {
        $student->load('info');
        // Create a new command instance
        $studentDTO = new UpdateStudentDTO();

        // Setting attributes related to UpdateStudentCommand (assuming these methods exist in UpdateStudentCommand)
        $studentDTO->setId($student->id);
        $studentDTO->setCode($request->get('code'));
        $studentDTO->setLastName($request->get('last_name'));
        $studentDTO->setFirstName($request->get('first_name')); // Corrected "last_name" to "first_name"
        // Handle thumbnail image upload or generate an avatar if no image is uploaded
        $path = null;
        if ($request->hasFile('thumbnail') && $request->file('thumbnail') instanceof UploadedFile) {
            $path = ImageHelper::uploadImage(
                $request->file('thumbnail'),
                Constants::PATH_THUMBNAIL_STUDENT,
            );
        }

        // Setting attributes related to UpdateStudentInfoCommand (instantiate and set)
        $studentInfoDTO = new UpdateStudentInfoDTO();
        $studentInfoDTO->setId($student->info?->id);
        $studentInfoDTO->setPersonEmail($request->get('person_email'));
        $studentInfoDTO->setGender($request->has('gender') ? Gender::from($request->get('gender')) : null);
        $studentInfoDTO->setPermanentResidence($request->get('permanent_residence'));
        $studentInfoDTO->setDob($request->get('dob'));
        $studentInfoDTO->setPob($request->get('pob'));
        $studentInfoDTO->setCountryside($request->get('countryside'));
        $studentInfoDTO->setAddress($request->get('address'));
        $studentInfoDTO->setTrainingType($request->has('training_type') ? TrainingType::from($request->get('training_type')) : null);
        $studentInfoDTO->setPhone($request->get('phone'));
        $studentInfoDTO->setNationality($request->get('nationality'));
        $studentInfoDTO->setCitizenIdentification($request->get('citizen_identification'));
        $studentInfoDTO->setEthnic($request->get('ethnic'));
        $studentInfoDTO->setReligion($request->get('religion'));
        $studentInfoDTO->setSocialPolicyObject($request->get('social_policy_object'));
        $studentInfoDTO->setNote($request->get('note'));
        $studentInfoDTO->setThumbnail($path ?? null);

        // Set the InfoCommand in the main UpdateStudentCommand
        $studentDTO->setInfoDTO($studentInfoDTO); // Assuming UpdateStudentCommand has setInfoCommand method

        return $studentDTO;
    }
}
