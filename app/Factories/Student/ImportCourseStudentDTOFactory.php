<?php

declare(strict_types=1);

namespace App\Factories\Student;

use App\DTO\Student\ImportCourseStudentDTO;
use App\Http\Requests\Admin\Student\ImportCourseStudentRequest;

class ImportCourseStudentDTOFactory
{
    /**
     * Create a CreateStudentCommand instance from a CreateStudentRequest.
     *
     * @param  ImportCourseStudentRequest  $request  The request containing student data.
     * @return ImportCourseStudentDTO The command object representing the student.
     */
    public static function make(ImportCourseStudentRequest $request): ImportCourseStudentDTO
    {
        // Create a new command instance
        $command = new ImportCourseStudentDTO();

        // Set properties of the command object from the request data
        $command->setFile($request->file('file'));
        $command->setAdmissionYearId((int)$request->get('admission_year_id'));

        return $command;
    }
}
