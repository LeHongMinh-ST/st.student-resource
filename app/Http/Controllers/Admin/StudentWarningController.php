<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\DeleteResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Factories\Warning\CreateStudentWarningDTOFactory;
use App\Factories\Warning\ImportStudentWarningDTOFactory;
use App\Factories\Warning\ListStudentWarningDTOFactory;
use App\Factories\Warning\UpdateStudentWarningDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentWarning\DeleteStudentWarningRequest;
use App\Http\Requests\Admin\StudentWarning\ImportStudentWarningRequest;
use App\Http\Requests\Admin\StudentWarning\ListStudentWarningRequest;
use App\Http\Requests\Admin\StudentWarning\ShowListStudentWarningRequest;
use App\Http\Requests\Admin\StudentWarning\ShowStudentWarningRequest;
use App\Http\Requests\Admin\StudentWarning\StoreStudentWarningRequest;
use App\Http\Requests\Admin\StudentWarning\UpdateStudentWarningRequest;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\StudentWarning\StudentWarningCollection;
use App\Http\Resources\StudentWarning\StudentWarningResource;
use App\Models\Student;
use App\Models\Warning;
use App\Services\Student\StudentWarningService;
use App\Supports\Constants;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

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
        private readonly StudentWarningService $studentWarningService
    ) {
    }

    /**
     * List of student warning session
     *
     * This endpoint lets you view a list of graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param ListStudentWarningRequest $request
     * @return StudentWarningCollection Returns the list of posts.
     */
    #[ResponseFromApiResource(StudentWarningCollection::class, Warning::class, Response::HTTP_OK, paginate: Constants::PAGE_LIMIT)]
    public function index(ListStudentWarningRequest $request): StudentWarningCollection
    {
        $command = ListStudentWarningDTOFactory::make($request);

        return new StudentWarningCollection($this->studentWarningService->getList($command));
    }

    /**
     * Create warning session
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param StoreStudentWarningRequest $request
     *
     * @return StudentWarningResource
     * @throws CreateResourceFailedException
     */
    #[ResponseFromApiResource(StudentWarningResource::class, Warning::class, Response::HTTP_CREATED)]
    public function store(StoreStudentWarningRequest $request): StudentWarningResource
    {
        $command = CreateStudentWarningDTOFactory::make($request);

        return new StudentWarningResource($this->studentWarningService->create($command));
    }

    /**
     * Show warning session
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param Warning $warning
     * @param ShowStudentWarningRequest $request
     * @return StudentWarningResource
     */
    #[ResponseFromApiResource(StudentWarningResource::class, Warning::class, Response::HTTP_OK)]
    public function show(Warning $warning, ShowStudentWarningRequest $request): StudentWarningResource
    {
        return new StudentWarningResource($warning->load('semester'));
    }

    /**
     * Update graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param UpdateStudentWarningRequest $request
     * @param Warning $warning
     *
     * @return StudentWarningResource
     *
     * @throws UpdateResourceFailedException
     */
    #[ResponseFromApiResource(StudentWarningResource::class, Warning::class, Response::HTTP_OK)]
    public function update(Warning $warning, UpdateStudentWarningRequest $request): StudentWarningResource
    {
        $command = UpdateStudentWarningDTOFactory::make($request, $warning->id);

        return new StudentWarningResource($this->studentWarningService->update($command));
    }

    /**
     * Delete warning session
     *
     * This endpoint allows student to delete a graduation ceremony.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param Warning $warning
     * @param DeleteStudentWarningRequest $request
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @throws DeleteResourceFailedException
     * @response 204 Indicates that the response will be a 204 No Content status.
     *
     */
    public function destroy(Warning $warning, DeleteStudentWarningRequest $request): JsonResponse
    {
        $this->studentWarningService->delete($warning->id);

        return $this->noContent();
    }

    /**
     * Show student warning session
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param Warning $warning
     * @param ShowListStudentWarningRequest $request
     * @return StudentCollection
     */
    #[ResponseFromApiResource(StudentCollection::class, Student::class, Response::HTTP_OK)]
    public function getStudents(Warning $warning, ShowListStudentWarningRequest $request): StudentCollection
    {
        $students = $warning->students()
            ->when($request->has('q'), function ($q) use ($request) {
                return $q->where(DB::raw("CONCAT(last_name, ' ', first_name)"), 'like', '%' . $request->get('q') . '%')
                    ->orWhere('email', 'like', '%' . $request->get('q') . '%')
                    ->orWhere('code', 'like', '%' . $request->get('q') . '%');
            })
            ->with(['info', 'currentClass', 'families'])
            ->paginate(Constants::PAGE_LIMIT);

        return new StudentCollection($students);
    }
}
