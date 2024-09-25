<?php

declare(strict_types=1);

use App\Enums\AuthApiSection;
use App\Http\Controllers\Student\AuthController;
use App\Http\Controllers\Student\RequestUpdateController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});
Route::middleware(['auth:' . AuthApiSection::Student->value])->group(function (): void {
    Route::prefix('request')->group(function (): void {
        Route::post('/', [RequestUpdateController::class, 'create']);
        Route::patch('/{id}/status', [RequestUpdateController::class, 'updateStatus']);
    });
});
