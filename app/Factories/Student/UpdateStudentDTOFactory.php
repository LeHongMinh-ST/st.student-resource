<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\CreateFamilyStudentDTO;
use App\DTO\Student\UpdateStudentDTO;
use App\DTO\Student\UpdateStudentInfoDTO;
use App\Enums\FamilyRelationship;
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
        //        if ($request->hasFile('thumbnail') && $request->file('thumbnail') instanceof UploadedFile) {
        //            $path = ImageHelper::uploadImage(
        //                $request->file('thumbnail'),
        //                Constants::PATH_THUMBNAIL_STUDENT,
        //            );
        //        }
        // Setting attributes related to UpdateStudentInfoCommand (instantiate and set)
        $studentInfoDTO = new UpdateStudentInfoDTO();
        $studentInfoDTO->setId($student->info?->id);
        $studentInfoDTO->setPersonEmail($request->input('info.person_email'));
        $studentInfoDTO->setGender($request->has('info.gender') ? Gender::from($request->input('info.gender')) : null);
        $studentInfoDTO->setPermanentResidence($request->input('info.permanent_residence'));
        $studentInfoDTO->setDob($request->input('info.dob'));
        $studentInfoDTO->setPob($request->input('info.pob'));
        $studentInfoDTO->setCountryside($request->input('info.countryside'));
        $studentInfoDTO->setAddress($request->input('info.address'));
        $studentInfoDTO->setTrainingType($request->has('info.training_type') ? TrainingType::from($request->input('info.training_type')) : null);
        $studentInfoDTO->setPhone($request->input('info.phone'));
        $studentInfoDTO->setNationality($request->input('info.nationality'));
        $studentInfoDTO->setCitizenIdentification($request->input('info.citizen_identification'));
        $studentInfoDTO->setEthnic($request->input('info.ethnic'));
        $studentInfoDTO->setReligion($request->input('info.religion'));
        $studentInfoDTO->setSocialPolicyObject($request->input('info.social_policy_object'));
        $studentInfoDTO->setNote($request->input('info.note'));
        //        $studentInfoDTO->setThumbnail($path ?? null);

        // Set the InfoCommand in the main UpdateStudentCommand
        $studentDTO->setInfoDTO($studentInfoDTO); // Assuming UpdateStudentCommand has setInfoCommand method
        $families = [];
        foreach ($request->get('families') as $family) {
            $familyDto = new CreateFamilyStudentDTO();
            $familyDto->setRelationship(FamilyRelationship::from($family['relationship']));
            $familyDto->setFullName($family['full_name']);
            $familyDto->setPhone($family['phone']);
            $familyDto->setJob($family['job']);
            $families[] = $familyDto;
        }
        $studentDTO->setFamilyStudentDTOArray($families);

        return $studentDTO;
    }
}
