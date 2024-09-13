<?php

declare(strict_types=1);

namespace App\Http\Controllers\System;

use App\Enums\AuthApiSection;
use App\Exceptions\LoginFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Authentication\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

/**
 * @group System API
 *
 * APIs for system admin
 *
 * @subgroup Auth
 *
 * @subgroupDescription APIs for auth system
 */
class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    /**
     * Login for system admin
     *
     * This endpoint lets you log in with system account
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
            section: AuthApiSection::System,
            remember: $request->has('remember')
        );

        return $this->responseWithToken($token, AuthApiSection::System);
    }

    /**
     * Logout for system admin
     *
     * This endpoint lets you log out system account
     *
     *
     * @responseFile 202 storage/responses/logout.json
     */
    public function logout(): JsonResponse
    {
        auth(AuthApiSection::System->value)->logout();

        return $this->accepted([
            'message' => 'Token revoked successfully.',
        ]);
    }
}
