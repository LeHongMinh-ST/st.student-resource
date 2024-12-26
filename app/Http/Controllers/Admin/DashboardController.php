<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GeneralClass\GeneralClassService;
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
        private readonly StudentService $studentService,
        private readonly GeneralClassService $generalClassService
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
     *  "class_count": 50,
     *  "status_graduaed_count": 20,
     *  "status_warning_count": 30
     * }
     */
    public function getStudentStatistical(): JsonResponse
    {
        $studentCount = $this->studentService->getTotalStudentStudy();
        $classCount = $this->generalClassService->getGeneralClassCount();
        $studentGraduaedCount = $this->studentService->getTotalStudentGraduated();
        $studentWarningCount = $this->studentService->getTotalStudentWarning();

        return $this->json([
            'student_count' => $studentCount,
            'class_count' => $classCount,
            'student_graduaed_count' => $studentGraduaedCount,
            'student_warning_count' => $studentWarningCount
        ]);
    }
}
