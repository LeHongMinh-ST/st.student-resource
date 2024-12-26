<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Http\JsonResponse;

class SemesterController extends Controller
{
    public function index(): JsonResponse
    {
        $semesters = Semester::query()
            ->with('schoolYear')
            ->orderBy(
                SchoolYear::select('start_year')
                    ->whereColumn('school_years.id', 'semesters.school_year_id'),
                'desc'
            )
            ->orderBy('semester', 'desc')
            ->limit(6)
            ->get();


        return response()->json($semesters);
    }
}
