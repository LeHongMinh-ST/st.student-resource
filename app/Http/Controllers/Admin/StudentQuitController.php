<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\DeleteResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Factories\Quit\CreateStudentQuitDTOFactory;
use App\Factories\Quit\ImportStudentQuitDTOFactory;
use App\Factories\Quit\ListStudentQuitDTOFactory;
use App\Factories\Quit\UpdateStudentQuitDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentQuit\DeleteStudentQuitRequest;
use App\Http\Requests\Admin\StudentQuit\ImportStudentQuitRequest;
use App\Http\Requests\Admin\StudentQuit\ListStudentQuitRequest;
use App\Http\Requests\Admin\StudentQuit\ShowListStudentQuitRequest;
use App\Http\Requests\Admin\StudentQuit\ShowStudentQuitRequest;
use App\Http\Requests\Admin\StudentQuit\StoreStudentQuitRequest;
use App\Http\Requests\Admin\StudentQuit\UpdateStudentQuitRequest;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\StudentQuit\StudentQuitCollection;
use App\Http\Resources\StudentQuit\StudentQuitResource;
use App\Models\Quit;
use App\Models\Student;
use App\Services\Student\StudentQuitService;
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

    /**
     * List of student quit session
     *
     * This endpoint lets you view a list of graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param ListStudentQuitRequest $request
     * @return StudentQuitCollection Returns the list of posts.
     */
    #[ResponseFromApiResource(StudentQuitCollection::class, Quit::class, Response::HTTP_OK, paginate: Constants::PAGE_LIMIT)]
    public function index(ListStudentQuitRequest $request): StudentQuitCollection
    {
        $command = ListStudentQuitDTOFactory::make($request);

        return new StudentQuitCollection($this->studentQuitService->getList($command));
    }

    /**
     * Create quit session
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param StoreStudentQuitRequest $request
     *
     * @return StudentQuitResource
     * @throws CreateResourceFailedException
     */
    #[ResponseFromApiResource(StudentQuitResource::class, Quit::class, Response::HTTP_CREATED)]
    public function store(StoreStudentQuitRequest $request): StudentQuitResource
    {
        $command = CreateStudentQuitDTOFactory::make($request);

        return new StudentQuitResource($this->studentQuitService->create($command));
    }

    /**
     * Show quit session
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param Quit $quit
     * @param ShowStudentQuitRequest $request
     * @return StudentQuitResource
     */
    #[ResponseFromApiResource(StudentQuitResource::class, Quit::class, Response::HTTP_OK)]
    public function show(Quit $quit, ShowStudentQuitRequest $request): StudentQuitResource
    {
        return new StudentQuitResource($quit);
    }

    /**
     * Update graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param UpdateStudentQuitRequest $request
     * @param Quit $quit
     *
     * @return StudentQuitResource
     *
     * @throws UpdateResourceFailedException
     */
    #[ResponseFromApiResource(StudentQuitResource::class, Quit::class, Response::HTTP_OK)]
    public function update(Quit $quit, UpdateStudentQuitRequest $request): StudentQuitResource
    {
        $command = UpdateStudentQuitDTOFactory::make($request, $quit->id);

        return new StudentQuitResource($this->studentQuitService->update($command));
    }

    /**
     * Delete quit session
     *
     * This endpoint allows student to delete a graduation ceremony.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param Quit $quit
     * @param DeleteStudentQuitRequest $request
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @throws DeleteResourceFailedException
     * @response 204 Indicates that the response will be a 204 No Content status.
     *
     */
    public function destroy(Quit $quit, DeleteStudentQuitRequest $request): JsonResponse
    {
        if ($quit->students()->count() > 0) {
            throw new DeleteResourceFailedException();
        }

        $this->studentQuitService->delete($quit->id);

        return $this->noContent();
    }

    /**
     * Show student quit session
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param Quit $quit
     * @param ShowListStudentQuitRequest $request
     * @return StudentCollection
     */
    #[ResponseFromApiResource(StudentCollection::class, Student::class, Response::HTTP_OK)]
    public function getStudents(Quit $quit, ShowListStudentQuitRequest $request): StudentCollection
    {
        $students = $quit->students()
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
