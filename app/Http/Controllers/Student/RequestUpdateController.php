<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Exceptions\CreateResourceFailedException;
use App\Factories\Student\CreateRequestUpdateStudentDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\RequestUpdateInfo\CreateRequestUpdateStudentRequest;
use App\Http\Resources\Student\StudentInfoUpdateResource;
use App\Models\StudentInfoUpdate;
use App\Services\StudentInfoRequest\StudentInfoUpdateService;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

/**
 * @group Student API
 *
 * APIs for student
 *
 * @subgroup RequestUpdate
 *
 * @subgroupDescription APIs for auth student
 */
class RequestUpdateController extends Controller
{
    public function __construct(
        private readonly StudentInfoUpdateService $studentInfoUpdateService,
    ) {
    }

    /**
     * Create Request Update Student
     *
     * This endpoint allows authenticated users to create a request to update an existing student's information.
     *
     * @authenticated Users must be authenticated to access this endpoint.
     *
     * @param CreateRequestUpdateStudentRequest $request The HTTP request object containing student update data.
     * @return StudentInfoUpdateResource Returns the newly created student info update resource.
     *
     * @throws CreateResourceFailedException
     */
    #[ResponseFromApiResource(StudentInfoUpdateResource::class, StudentInfoUpdate::class, Response::HTTP_CREATED)]
    public function create(CreateRequestUpdateStudentRequest $request): StudentInfoUpdateResource
    {
        $dto = CreateRequestUpdateStudentDTOFactory::make($request);

        $studentInfoUpdate = $this->studentInfoUpdateService->create($dto);

        return new StudentInfoUpdateResource($studentInfoUpdate->load('families'));
    }

    public function updateStatus(): void
    {

    }
}
