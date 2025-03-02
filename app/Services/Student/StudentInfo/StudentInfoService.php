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
        $studentInfo = StudentInfo::create(array_merge($createStudentFileDTO->getStudentInfoDTO()->toArray(), [
            'student_id' => $student->id,
        ]));

        if (count($createStudentFileDTO->getFamily()) > 0) {
            // map studentInfoId in data family
            foreach ($createStudentFileDTO->getFamily() as $familyDTO) {
                $student->families()->create($familyDTO->toArray());
            }
        }

        return $studentInfo;
    }

    public function updateByStudentFileCourse(int $id, CreateStudentCourseByFileDTO $createStudentFileDTO): StudentInfo
    {
        $studentInfo = StudentInfo::where([
            'student_id' => $id,
        ])->update(array_merge($createStudentFileDTO->getStudentInfoDTO()->toArray()));

        return $studentInfo;
    }

    public function update(UpdateStudentInfoDTO $updateStudentInfoDTO): StudentInfo
    {
        $infoStudent = StudentInfo::where('student_id', $updateStudentInfoDTO->getId())->first();
        $infoStudent->update($updateStudentInfoDTO->toArray());

        return $infoStudent;
    }
}
