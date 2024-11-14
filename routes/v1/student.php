<?php

declare(strict_types=1);

use App\Enums\AuthApiSection;
use App\Http\Controllers\Student\AuthController;
use App\Http\Controllers\Student\CitiController;
use App\Http\Controllers\Student\EmploymentSurveyResponseController;
use App\Http\Controllers\Student\ReflectController;
use App\Http\Controllers\Student\RequestUpdateController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\SurveyPeriodController;
use App\Http\Controllers\Student\TrainingIndustryController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::prefix('external')->group(function (): void {
    Route::post('/employment-survey-response', [EmploymentSurveyResponseController::class, 'store']);
    Route::get('/employment-survey-response-search', [EmploymentSurveyResponseController::class, 'search']);
    Route::get('/student-info-search', [StudentController::class, 'search']);
    Route::get('/survey-periods/{surveyPeriod}', [SurveyPeriodController::class, 'show']);
    Route::get('/training-industries', [TrainingIndustryController::class, 'index']);
    Route::get('/cities', [CitiController::class, 'index']);
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
