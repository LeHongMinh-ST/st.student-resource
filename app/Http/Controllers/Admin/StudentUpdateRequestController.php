<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exceptions\UpdateResourceFailedException;
use App\Factories\RequestUpdateStudent\ChangeStatusRequestUpdateStudentDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentRequestUpdate\ChangeStatusRequestUpdateStudentRequest;
use App\Http\Requests\Admin\StudentRequestUpdate\ShowRequestUpdateStudentRequest;
use App\Http\Resources\Student\StudentInfoUpdateResource;
use App\Models\StudentInfoUpdate;
use App\Services\StudentInfoRequest\ApproveStudentUpdateService;
use Illuminate\Http\JsonResponse;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup RequestUpdate
 *
 * @subgroupDescription APIs for auth student
 */
class StudentUpdateRequestController extends Controller
{
    public function __construct(
        private readonly ApproveStudentUpdateService $approveStudentUpdateService
    ) {
    }

    public function index(): void
    {

    }

    /**
     * Update student
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param ChangeStatusRequestUpdateStudentRequest $request The HTTP request object containing student data.
     * @return JsonResponse Returns the newly UserResource as a resource.
     *
     * @throws UpdateResourceFailedException
     */
    public function updateStatus(mixed $id, ChangeStatusRequestUpdateStudentRequest $request): JsonResponse
    {
        $command = ChangeStatusRequestUpdateStudentDTOFactory::make($request, $id);

        $this->approveStudentUpdateService->updateStatus($command);

        return $this->accepted([
            'message' => 'Updated status successfully.',
        ]);
    }

    /**
     * Show Request Update Student
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param ShowRequestUpdateStudentRequest $request
     * @param StudentInfoUpdate $studentInfoUpdate
     * @return StudentInfoUpdateResource Returns the newly UserResource as a resource.
     */
    public function show(ShowRequestUpdateStudentRequest $request, StudentInfoUpdate $studentInfoUpdate): StudentInfoUpdateResource
    {
        return new StudentInfoUpdateResource($studentInfoUpdate->load('families'));
    }
}
