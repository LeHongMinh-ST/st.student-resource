<?php

declare(strict_types=1);

use App\Enums\AuthApiSection;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\GeneralClassController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::middleware(['auth:api' . AuthApiSection::Admin->value])->group(function (): void {
    Route::prefix('classes')->group(function (): void {
        Route::get('/', [GeneralClassController::class, 'index']);
        Route::post('/', [GeneralClassController::class, 'store']);
        Route::get('/{generalClass}', [GeneralClassController::class, 'show']);
        Route::patch('/{generalClass}', [GeneralClassController::class, 'update']);
        Route::delete('/{generalClass}', [GeneralClassController::class, 'destroy']);
    });
});
