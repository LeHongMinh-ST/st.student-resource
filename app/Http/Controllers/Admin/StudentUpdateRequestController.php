<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Factories\Student\ChangeStatusRequestUpdateStudentDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Student\ChangeStatusRequestUpdateStudentRequest;
use App\Services\StudentInfoRequest\ApproveStudentUpdateService;
use Illuminate\Http\JsonResponse;

class StudentUpdateRequestController extends Controller
{
    public function __construct(
        private readonly ApproveStudentUpdateService $approveStudentUpdate
    ) {}

    /**
     * Update student
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ChangeStatusRequestUpdateStudentRequest  $request  The HTTP request object containing student data.
     * @return JsonResponse Returns the newly UserResource as a resource.
     *
     */
    public function update(mixed $id, ChangeStatusRequestUpdateStudentRequest $request): JsonResponse
    {
        $command = ChangeStatusRequestUpdateStudentDTOFactory::make($request, $id);

        $this->approveStudentUpdate->update($command);

        return $this->accepted([
            'message' => 'Updated status successfully.',
        ]);
    }
}
