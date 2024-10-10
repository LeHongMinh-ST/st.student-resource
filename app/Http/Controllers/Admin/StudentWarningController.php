<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentWarning;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Student Warning
 *
 * @subgroupDescription APIs for Graduation
 */
class StudentWarningController extends Controller
{
    public function __construct(
        private readonly StudentWarning $studentWarning
    ) {
    }

    public function index(): void
    {

    }

    public function store(): void
    {

    }

    public function show(StudentWarning $studentWarning): void
    {

    }

    public function update(StudentWarning $studentWarning): void
    {

    }

    public function destroy(StudentWarning $studentWarning): void
    {

    }
}
