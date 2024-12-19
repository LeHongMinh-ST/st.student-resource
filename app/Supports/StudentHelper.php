<?php

declare(strict_types=1);

namespace App\Supports;

use App\Enums\AuthApiSection;
use App\Enums\UserRole;
use App\Models\Student;

class StudentHelper
{
    /**
     * Generate the email address for a student based on their unique code.
     *
     * This function appends the student's unique code to a predefined email domain
     * specified in the configuration file under 'vnua.mail_student'.
     *
     * @param string $code The unique code assigned to the student.
     * @return string The generated email address for the student.
     */
    public static function makeEmailStudent(string $code): string
    {
        return $code . config('vnua.mail_student');
    }

    public static function checkUserStudent(int|string $id): bool
    {
        $student = Student::query()->findOrFail($id);

        $auth = auth(AuthApiSection::Admin->value)->user();

        if (!$auth) {
            return false;
        }

        if (UserRole::Teacher === $auth->role) {
            return static::isTeacher($auth->id, $student->generalClass)
;        }

        return $auth->faculty_id === $student->faculty_id;
    }

    private static function isTeacher(int|string $id, array $classes): bool
    {
        foreach ($classes as $class) {
            if ($class->teacher_id === $id || $class->sub_teacher_id === $id) {
                return true;
            }
        }

        return false;
    }

}
