<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Enums\AuthApiSection;
use App\Exceptions\InvalidTokenException;
use App\Exceptions\LoginFailedException;
use App\Models\User;
use App\Traits\AuthSupportTrait;
use App\Values\AuthValues;
use App\Values\RefreshTokenClaimsValues;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    use AuthSupportTrait;

    /**
     * Handles the login process.
     *
     * @throws LoginFailedException if authentication fails
     */
    public function login(string $userName, string $password, AuthApiSection $section = AuthApiSection::Admin, bool $remember = false): AuthValues
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
        // Initialize the refresh token as null
        $refreshToken = null;

        // If the "remember me" option is set, create a refresh token
        if ($remember) {
            $refreshToken = $this->createRefreshTokenAction($section);
        }

        // Return the authentication values, including the token and optionally the refresh token
        return new AuthValues($token, $refreshToken);
    }

    /**
     * @throws InvalidTokenException
     * @throws JWTException
     */
    public function refreshToken(string $refreshToken, AuthApiSection $section = AuthApiSection::Admin): AuthValues
    {
        // Decode the provided refresh token
        $refreshTokenDecode = JWTAuth::getJWTProvider()->decode($refreshToken);

        // Get the authentication section from the decoded token
        $authSection = AuthApiSection::from($refreshTokenDecode['section']);

        // Create refresh token claims values from the decoded token
        $refreshValues = RefreshTokenClaimsValues::formArray($authSection, $refreshTokenDecode);

        // Check if the refresh token has timed out
        if ($refreshValues->isTimeOut()) {
            throw new JWTException();
        }

        // Check if the section of the token matches the API section from the command
        if (! $refreshValues->isSection($section)) {
            throw new JWTException();
        }

        // Invalidate the current authentication token
        auth($authSection->value)->invalidate(true);

        // Find the user by ID using the decoded refresh token values
        $user = User::query()->findOrFail($refreshValues->getUserId());

        // Generate a new authentication token for the user
        $token = auth($authSection->value)->login($user);

        // Return the new authentication values, with the token and no refresh token
        return new AuthValues((string) $token, null);
    }

    /**
     * Generates a refresh token for the given AuthApiSection.
     *
     * @param  AuthApiSection  $apiSection  The API section for which to create the refresh token.
     * @return string The encoded refresh token.
     */
    protected function createRefreshTokenAction(AuthApiSection $apiSection): string
    {
        // Create an instance of RefreshTokenClaimsValues with the provided AuthApiSection
        $refreshTokenValue = new RefreshTokenClaimsValues($apiSection);

        // Encode the claims to generate a JWT refresh token
        return JWTAuth::getJWTProvider()->encode($refreshTokenValue->toArray());
    }
}
