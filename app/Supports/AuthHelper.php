<?php

declare(strict_types=1);

namespace App\Supports;

use App\Enums\AuthApiSection;
use App\Enums\StudentRole;

class AuthHelper
{
    /**
     * Checks if the authenticated user's role matches any of the provided roles.
     *
     * @param  StudentRole  ...$roles  One or more roles to check against the authenticated user's role.
     * @return bool Returns true if the authenticated user's role is in the list of provided roles, false otherwise.
     */
    public static function isRoleStudent(StudentRole ...$roles): bool
    {
        // Get the authentication details for the Student section
        $auth = auth(AuthApiSection::Student->value);

        // Retrieve the role of the authenticated user
        $role = $auth->user()->role;

        // Check if the user's role is in the list of provided roles
        return in_array($role, $roles);
    }
}
