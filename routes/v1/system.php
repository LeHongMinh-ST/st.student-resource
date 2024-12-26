<?php

declare(strict_types=1);

use App\Http\Controllers\System\AuthController;
use App\Http\Controllers\System\FacultyController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:system')->group(function (): void {
    Route::prefix('faculty')->group(function (): void {
        Route::get('/', [FacultyController::class, 'index']);
        Route::post('/', [FacultyController::class, 'store']);
    });
});
