<?php

declare(strict_types=1);

namespace App\Supports;

use Illuminate\Support\Str;

class PasswordHelper
{
    /**
     * Generates a password based on the application environment.
     * If the application is in production, it generates a random password.
     * Otherwise, it uses a default password from the configuration.
     */
    public static function makePassword(): string
    {
        // Check if the application is running in production
        if (app()->isProduction()) {
            // Return a random password of length specified by MAX_LENGTH_PASSWORD constant
            return Str::random(Constants::MAX_LENGTH_PASSWORD);
        }

        // Return the default password specified in the configuration
        return config('app.password_default');

    }
}
