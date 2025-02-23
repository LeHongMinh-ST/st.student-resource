<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Enums\AuthApiSection;
use App\Exceptions\ConflictRecordException;
use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\DeleteResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Factories\RequestUpdateStudent\ChangeStatusRequestUpdateStudentDTOFactory;
use App\Factories\RequestUpdateStudent\CreateRequestUpdateStudentDTOFactory;
use App\Factories\RequestUpdateStudent\ListRequestUpdateStudentDTOFactory;
use App\Factories\RequestUpdateStudent\UpdateRequestUpdateStudentDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\RequestUpdateInfo\ChangeStatusRequestUpdateStudentRequest;
use App\Http\Requests\Student\RequestUpdateInfo\CreateRequestUpdateStudentRequest;
use App\Http\Requests\Student\RequestUpdateInfo\DeleteRequestUpdateStudentRequest;
use App\Http\Requests\Student\RequestUpdateInfo\ListMyRequestUpdateStudentRequest;
use App\Http\Requests\Student\RequestUpdateInfo\ListRequestUpdateStudentRequest;
use App\Http\Requests\Student\RequestUpdateInfo\ShowRequestUpdateStudentRequest;
use App\Http\Requests\Student\RequestUpdateInfo\UpdateRequestUpdateStudentRequest;
use App\Http\Resources\Student\StudentInfoUpdateCollection;
use App\Http\Resources\Student\StudentInfoUpdateResource;
use App\Models\StudentInfoUpdate;
use App\Services\StudentInfoRequest\ApproveStudentUpdateService;
use App\Services\StudentInfoRequest\StudentInfoUpdateService;
use App\Supports\Constants;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

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
        private readonly StudentInfoUpdateService    $studentInfoUpdateService,
        private readonly ApproveStudentUpdateService $approveStudentUpdateService
    ) {
    }

    /**
     * List of request update for President
     *
     * This endpoint lets you views list a Request update
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListRequestUpdateStudentRequest $request
     * @return StudentInfoUpdateCollection Returns the list of GeneralClass.
     */
    #[ResponseFromApiResource(StudentInfoUpdateCollection::class, StudentInfoUpdate::class, Response::HTTP_OK, with: [
        'families',
    ], paginate: Constants::PAGE_LIMIT)]
    public function index(ListRequestUpdateStudentRequest $request): StudentInfoUpdateCollection
    {
        $auth = auth('student')->user();
        $auth->load('currentClass');
        $dto = ListRequestUpdateStudentDTOFactory::make(
            request: $request,
            classId: $auth->currentClass->id,
        );

        $studentUpdate = $this->studentInfoUpdateService->getList($dto);

        return new StudentInfoUpdateCollection($studentUpdate);
    }


    /**
     * List of request update for current student
     *
     * This endpoint lets you views list a Request update
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListMyRequestUpdateStudentRequest $request
     * @return StudentInfoUpdateCollection Returns the list of GeneralClass.
     */
    #[ResponseFromApiResource(StudentInfoUpdateCollection::class, StudentInfoUpdate::class, Response::HTTP_OK, with: [
        'families',
    ], paginate: Constants::PAGE_LIMIT)]
    public function myRequest(ListMyRequestUpdateStudentRequest $request): StudentInfoUpdateCollection
    {
        $dto = ListRequestUpdateStudentDTOFactory::make(
            request: $request,
            studentId: auth(AuthApiSection::Student->value)->id(),
        );

        $studentUpdate = $this->studentInfoUpdateService->getMyList($dto);

        return new StudentInfoUpdateCollection($studentUpdate);
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
     * @throws ConflictRecordException
     */
    #[ResponseFromApiResource(StudentInfoUpdateResource::class, StudentInfoUpdate::class, Response::HTTP_CREATED)]
    public function create(CreateRequestUpdateStudentRequest $request): StudentInfoUpdateResource
    {
        $dto = CreateRequestUpdateStudentDTOFactory::make($request);

        $studentInfoUpdate = $this->studentInfoUpdateService->create($dto);

        return new StudentInfoUpdateResource($studentInfoUpdate->load('families'));
    }

    /**
     * Update Request Update Student
     *
     * This endpoint allows authenticated users to create a request to update an existing student's information.
     *
     * @authenticated Users must be authenticated to access this endpoint.
     *
     * @param UpdateRequestUpdateStudentRequest $request The HTTP request object containing student update data.
     * @return StudentInfoUpdateResource Returns the newly created student info update resource.
     *
     * @throws UpdateResourceFailedException
     */
    public function update(UpdateRequestUpdateStudentRequest $request, StudentInfoUpdate $studentInfoUpdate): StudentInfoUpdateResource
    {
        $dto = UpdateRequestUpdateStudentDTOFactory::make($request, $studentInfoUpdate);

        $studentInfoUpdate = $this->studentInfoUpdateService->update($dto);

        return new StudentInfoUpdateResource($studentInfoUpdate->load('families'));
    }

    /**
     * Update Status Request Update Student
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param ChangeStatusRequestUpdateStudentRequest $request The HTTP request object containing student data.
     * @return JsonResponse Returns the newly UserResource as a resource.
     *
     * @throws UpdateResourceFailedException
     */
    public function updateStatus(ChangeStatusRequestUpdateStudentRequest $request, StudentInfoUpdate $studentInfoUpdate): JsonResponse
    {
        $command = ChangeStatusRequestUpdateStudentDTOFactory::make($request, $studentInfoUpdate->id);

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

    /**
     * Delete Request Update Student
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param DeleteRequestUpdateStudentRequest $request
     * @param StudentInfoUpdate $studentInfoUpdate
     * @return JsonResponse Returns the newly UserResource as a resource.
     *
     * @throws DeleteResourceFailedException
     */
    public function destroy(DeleteRequestUpdateStudentRequest $request, StudentInfoUpdate $studentInfoUpdate): JsonResponse
    {
        $this->studentInfoUpdateService->delete($studentInfoUpdate);
        return $this->noContent();
    }
}
