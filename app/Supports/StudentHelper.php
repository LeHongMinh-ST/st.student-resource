<?php

declare(strict_types=1);

namespace App\Supports;

class StudentHelper
{
    /**
     * Generate the email address for a student based on their unique code.
     *
     * This function appends the student's unique code to a predefined email domain
     * specified in the configuration file under 'vnua.mail_student'.
     *
     * @param  string  $code  The unique code assigned to the student.
     * @return string The generated email address for the student.
     */
    public static function makeEmailStudent(string $code): string
    {
        return $code . config('vnua.mail_student');
    }
}
