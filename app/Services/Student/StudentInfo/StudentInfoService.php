<?php

declare(strict_types=1);

namespace App\Services\Student\StudentInfo;

use App\DTO\Student\CreateStudentCourseByFileDTO;
use App\DTO\Student\CreateStudentDTO;
use App\DTO\Student\UpdateStudentInfoDTO;
use App\Exceptions\CreateResourceFailedException;
use App\Models\Student;
use App\Models\StudentInfo;

class StudentInfoService
{
    /**
     * @throws CreateResourceFailedException
     */
    public function createByStudent(Student $student, CreateStudentDTO $createStudentDTO): StudentInfo
    {
        return StudentInfo::create([
            'student_id' => $student->id,
            'thumbnail' => $createStudentDTO->getThumbnail(),
            'gender' => $createStudentDTO->getGender(),
        ]);
    }

    public function createByStudentFileCourse(Student $student, CreateStudentCourseByFileDTO $createStudentFileDTO): StudentInfo
    {
        return StudentInfo::create([
            'student_id' => $student->id,
            'thumbnail' => $createStudentFileDTO->getThumbnail(),
            'gender' => $createStudentFileDTO->getGender(),
            'phone' => $createStudentFileDTO->getPhoneNumber(),
            'dob' => $createStudentFileDTO->getDob(),
        ]);
    }

    public function update(UpdateStudentInfoDTO $updateStudentInfoDTO): StudentInfo
    {
        $infoStudent = StudentInfo::where('student_id', $updateStudentInfoDTO->getId())->first();
        $infoStudent->update($updateStudentInfoDTO->toArray());

        return $infoStudent;
    }
}
