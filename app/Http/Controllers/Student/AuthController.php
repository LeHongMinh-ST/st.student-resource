<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Enums\AuthApiSection;
use App\Exceptions\InvalidTokenException;
use App\Exceptions\LoginFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RefreshRequest;
use App\Http\Resources\Student\StudentResource;
use App\Models\Student;
use App\Services\Auth\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Student API
 *
 * APIs for student
 *
 * @subgroup Auth
 *
 * @subgroupDescription APIs for auth student
 */
class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    /**
     * Login for student
     *
     * This endpoint lets you log in with student account
     *
     * @param LoginRequest $request
     * @return JsonResponse
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
            section: AuthApiSection::Student,
            remember: $request->get('remember')
        );

        return $this->responseWithToken($token, AuthApiSection::Student);
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
            auth(AuthApiSection::Student->value)->logout();
        } catch (Exception $exception) {
            Log::error('Error logout', [
                'section' => AuthApiSection::Student,
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
     * This endpoint lets you get profile current student
     *
     * @authenticated
     */
    #[ResponseFromApiResource(
        StudentResource::class,
        Student::class,
        Response::HTTP_OK,
        with: ['info', 'faculty', 'families']
    )]
    public function profile(): StudentResource
    {
        // Retrieve the authenticated user with the admin API section
        $user = auth(AuthApiSection::Student->value)->user();

        // Load the 'faculty' relationship for the user
        $user->load(['info', 'faculty', 'families']);

        // Wrap the user data in a UserResource and return it
        return new StudentResource($user);
    }
}
