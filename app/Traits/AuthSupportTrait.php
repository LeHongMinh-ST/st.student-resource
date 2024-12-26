<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\AuthApiSection;

trait AuthSupportTrait
{
    /**
     * Determines the appropriate username field based on the user type and provided username.
     *
     * @param  string  $userName  The provided username.
     * @param  AuthApiSection  $type  The type of authentication section (e.g., Admin or other).
     * @return string The username field to be used for authentication.
     */
    public function username(string $userName, AuthApiSection $type): string
    {
        // Determine the username field based on the authentication section type
        $userNameField = in_array($type, [AuthApiSection::Admin, AuthApiSection::System]) ? 'user_name' : 'code';

        // Check if the provided username is a valid email address
        // If so, return 'email', otherwise return the determined username field
        return filter_var($userName, FILTER_VALIDATE_EMAIL) ? 'email' : $userNameField;
    }
}
