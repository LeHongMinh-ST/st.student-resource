<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentQuit;
use App\Services\Student\StudentQuitService;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Student Quit
 *
 * @subgroupDescription APIs for Graduation
 */
class StudentQuitController extends Controller
{
    public function __construct(
        private readonly StudentQuitService $studentQuitService
    ) {
    }

    public function index(): void
    {

    }

    public function store(): void
    {

    }

    public function show(StudentQuit $studentQuit): void
    {

    }

    public function update(StudentQuit $studentQuit): void
    {

    }

    public function destroy(StudentQuit $studentQuit): void
    {

    }
}
