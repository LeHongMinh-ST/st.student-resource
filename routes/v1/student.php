<?php

declare(strict_types=1);

use App\Enums\AuthApiSection;
use App\Http\Controllers\Student\AuthController;
use App\Http\Controllers\Student\ReflectController;
use App\Http\Controllers\Student\RequestUpdateController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});
Route::middleware(['auth:' . AuthApiSection::Student->value])->group(function (): void {

    Route::prefix('auth')->group(function (): void {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::put('/reset-password', [AuthController::class, 'resetPassword']);
    });

    Route::prefix('request')->group(function (): void {
        Route::get('/', [RequestUpdateController::class, 'index']);
        Route::get('/my-request', [RequestUpdateController::class, 'myRequest']);
        Route::post('/', [RequestUpdateController::class, 'create']);
        Route::patch('/{studentInfoUpdate}', [RequestUpdateController::class, 'update']);
        Route::get('/{studentInfoUpdate}', [RequestUpdateController::class, 'show']);
        Route::patch('/{studentInfoUpdate}/status', [RequestUpdateController::class, 'updateStatus']);
        Route::delete('/{studentInfoUpdate}', [RequestUpdateController::class, 'destroy']);
    });

    Route::prefix('reflects')->group(function (): void {
        Route::get('/', [ReflectController::class, 'index']);
        Route::post('/', [ReflectController::class, 'store']);
        Route::get('/{reflect}', [ReflectController::class, 'show']);
        Route::patch('/{reflect}', [ReflectController::class, 'update']);
        Route::delete('/{reflect}', [ReflectController::class, 'destroy']);
    });
});
