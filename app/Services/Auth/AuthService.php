<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Enums\AuthApiSection;
use App\Exceptions\LoginFailedException;
use App\Traits\AuthSupportTrait;

class AuthService
{
    use AuthSupportTrait;

    public function login(string $userName, string $password, AuthApiSection $section = AuthApiSection::Admin, bool $remember = false): void
    {


        // Create an array of credentials using the username and password from the login command
        $credentials = [
            $this->username($userName, $section) => $userName,
            'password' => $password,
        ];

        // Attempt to authenticate the user with the provided credentials and token type
        if (! $token = auth($section->value)->attempt($credentials)) {
            // Throw an exception if authentication fails
            throw new LoginFailedException();
        }
    }
}
