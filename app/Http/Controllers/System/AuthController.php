<?php

declare(strict_types=1);

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Authentication\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
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
     */
    public function login(LoginRequest $request): JsonResponse
    {

    }
}
