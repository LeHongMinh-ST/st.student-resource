<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AuthApiSection;
use App\Exceptions\InvalidTokenException;
use App\Exceptions\LoginFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RefreshRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\Auth\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Authentication
 *
 * @subgroupDescription APIs for authentication admin
 */
class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    /**
     * Login for admin
     *
     * This endpoint lets you log in with admin account
     *
     *
     * @responseFile 200 storage/responses/login.json
     *
     * @throws LoginFailedException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login(
            userName: $request->get('user_name'),
            password: $request->get('password'),
            remember: $request->get('remember', false)
        );

        return $this->responseWithToken($token, AuthApiSection::Admin);
    }

    /**
     * Logout for student
     *
     * This endpoint lets you log out admin account
     *
     *
     * @responseFile 202 storage/responses/logout.json
     */
    public function logout(): JsonResponse
    {
        try {
            auth(AuthApiSection::Admin->value)->logout();
        } catch (Exception $exception) {
            Log::error('Error logout', [
                'section' => AuthApiSection::Admin,
                'message' => $exception->getMessage(),
            ]);
        }

        return $this->accepted([
            'message' => 'Token revoked successfully.',
        ]);
    }

    /**
     * Refresh token
     *
     * This endpoint lets you refresh access token for admin
     *
     * @responseFile 200 storage/responses/login.json
     *
     * @throws InvalidTokenException|JWTException
     */
    public function refresh(RefreshRequest $request): JsonResponse
    {
        // Dispatch the refresh token command immediately and handle it with the RefreshTokenHandler
        try {
            $token = $this->authService->refreshToken(
                refreshToken: $request->get('refresh_token'),
                section: AuthApiSection::Student
            );
        } catch (InvalidTokenException) {
            throw new InvalidTokenException();
        }

        // Return a JSON response with the new token and the admin API section
        return $this->responseWithToken($token, AuthApiSection::Student);
    }

    /**
     * Get profile
     *
     * This endpoint lets you get profile current user
     *
     * @authenticated
     *
     * @return UserResource
     */
    #[ResponseFromApiResource(
        UserResource::class,
        User::class,
        Response::HTTP_OK,
        with: ['faculty']
    )]
    public function profile(): UserResource
    {
        // Retrieve the authenticated user with the admin API section
        $user = auth(AuthApiSection::Admin->value)->user();

        // Load the 'faculty' relationship for the user
        $user->load('faculty');

        // Wrap the user data in a UserResource and return it
        return new UserResource($user);
    }
}
