<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Student\StudentService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Dashboard
 *
 * @subgroupDescription APIs for dashbcard admin
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly StudentService $studentService
    ) {

    }

    /**
     * Get dashboard statistical
     *
     * This endpoint lets you views dashboard statistical
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     * @response {
     *  "student_count": 100,
     * }
     */
    public function getStudentStatistical(): JsonResponse
    {
        $studentCount = $this->studentService->getTotalStudentStudy();

        return $this->json([
            'student_count' => $studentCount
        ]);
    }
}
