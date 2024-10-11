<?php

declare(strict_types=1);

use App\Enums\AuthApiSection;
use App\Http\Controllers\Admin\AdmissionYearController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\GeneralClassController;
use App\Http\Controllers\Admin\GraduationController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ReflectController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentQuitController;
use App\Http\Controllers\Admin\StudentUpdateRequestController;
use App\Http\Controllers\Admin\StudentWarningController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::middleware(['auth:' . AuthApiSection::Admin->value])->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::get('profile', [AuthController::class, 'profile']);
    });

    Route::prefix('classes')->group(function (): void {
        Route::get('/', [GeneralClassController::class, 'index']);
        Route::post('/', [GeneralClassController::class, 'store']);
        Route::get('/{generalClass}', [GeneralClassController::class, 'show']);
        Route::patch('/{generalClass}', [GeneralClassController::class, 'update']);
        Route::delete('/{generalClass}', [GeneralClassController::class, 'destroy']);
    });

    Route::Resource('departments', DepartmentController::class);

    Route::prefix('users')->group(function (): void {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::post('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
        Route::get('/{user}', [UserController::class, 'show']);
    });

    Route::prefix('admission-year')->group(function (): void {
        Route::get('/', [AdmissionYearController::class, 'index']);
        Route::get('/{admissionYear}/student-file-imports', [AdmissionYearController::class, 'getListStudentFileImports']);
    });

    Route::prefix('students')->group(function (): void {
        Route::get('/', [StudentController::class, 'index']);
        Route::post('/', [StudentController::class, 'store']);
        Route::post('/import-course', [StudentController::class, 'importCourse']);
        Route::get('/import-course/{excelImportFileError}/download-error', [StudentController::class, 'downloadErrorImportCourse']);
        Route::get('/import-course/download-template', [StudentController::class, 'downloadTemplateImportCourse']);
        Route::get('/{student}', [StudentController::class, 'show']);
        Route::post('/{student}', [StudentController::class, 'update']);
        Route::delete('/{student}', [StudentController::class, 'destroy']);
    });

    Route::prefix('posts')->group(function (): void {
        Route::get('/', [PostController::class, 'index']);
        Route::get('/list-publish', [PostController::class, 'getListPostPublish']);
        Route::post('/', [PostController::class, 'store']);
        Route::patch('/{post}', [PostController::class, 'update']);
        Route::get('/{post}', [PostController::class, 'show']);
        Route::delete('/{post}', [PostController::class, 'destroy']);
    });

    Route::prefix('student-requests')->group(function (): void {
        Route::get('/', [StudentUpdateRequestController::class, 'index']);
        Route::get('/{id}', [StudentUpdateRequestController::class, 'show']);
        Route::patch('/{id}', [StudentUpdateRequestController::class, 'updateStatus']);
    });

    Route::prefix('reflects')->group(function (): void {
        Route::get('/', [ReflectController::class, 'index']);
        Route::get('/{reflect}', [ReflectController::class, 'show']);
        Route::patch('/{reflect}/status', [ReflectController::class, 'update-status']);
    });

    Route::prefix('graduates')->group(function (): void {
        Route::get('/', [GraduationController::class, 'index']);
        Route::post('/', [GraduationController::class, 'store']);
        Route::post('/import-student', [GraduationController::class, 'importStudent']);
        Route::get('/import-student/{excelImportFileError}/download-error', [GraduationController::class, 'downloadErrorImportCourse']);
        Route::get('/import-student/download-template', [GraduationController::class, 'downloadTemplateImport']);
        Route::get('/{graduationCeremony}', [GraduationController::class, 'show']);
        Route::patch('/{graduationCeremony}', [GraduationController::class, 'update']);
        Route::delete('/{graduationCeremony}', [GraduationController::class, 'destroy']);
    });

    Route::prefix('warning')->group(function (): void {
        Route::get('/', [StudentWarningController::class, 'index']);
        Route::post('/', [StudentWarningController::class, 'store']);
        Route::post('/import-student', [StudentWarningController::class, 'importStudent']);
        Route::get('/import-student/{excelImportFileError}/download-error', [StudentWarningController::class, 'importStudent']);
        Route::get('/import-student/download-template', [StudentWarningController::class, 'downloadTemplateImport']);
        Route::get('/{studentWarning}', [StudentWarningController::class, 'show']);
        Route::patch('/{studentWarning}', [StudentWarningController::class, 'update']);
        Route::delete('/{studentWarning}', [StudentWarningController::class, 'destroy']);
    });

    Route::prefix('quit')->group(function (): void {
        Route::get('/', [StudentQuitController::class, 'index']);
        Route::post('/', [StudentQuitController::class, 'store']);
        Route::post('/import-student', [StudentQuitController::class, 'importStudent']);
        Route::post('/import-student/{excelImportFileError}/download', [StudentQuitController::class, 'importStudent']);
        Route::get('/import-student/download-template', [StudentQuitController::class, 'downloadTemplateImport']);
        Route::get('/{studentQuit}', [StudentQuitController::class, 'show']);
        Route::patch('/{studentQuit}', [StudentQuitController::class, 'update']);
        Route::delete('/{studentQuit}', [StudentQuitController::class, 'destroy']);
    });
});
