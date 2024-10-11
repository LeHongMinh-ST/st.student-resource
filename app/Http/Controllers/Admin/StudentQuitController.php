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
use App\Http\Requests\Admin\StudentQuit\ShowStudentQuitRequest;
use App\Http\Requests\Admin\StudentQuit\StoreStudentQuitRequest;
use App\Http\Requests\Admin\StudentQuit\UpdateStudentQuitRequest;
use App\Http\Resources\StudentQuit\StudentQuitCollection;
use App\Http\Resources\StudentQuit\StudentQuitResource;
use App\Models\Quit;
use App\Models\StudentQuit;
use App\Services\Student\StudentQuitService;
use App\Supports\Constants;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
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
     * @param StudentQuit $studentQuit
     * @param ShowStudentQuitRequest $request
     * @return StudentQuitResource
     */
    #[ResponseFromApiResource(StudentQuitResource::class, Quit::class, Response::HTTP_OK)]
    public function show(StudentQuit $studentQuit, ShowStudentQuitRequest $request): StudentQuitResource
    {
        return new StudentQuitResource($studentQuit);
    }

    /**
     * Update graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param UpdateStudentQuitRequest $request
     * @param StudentQuit $studentQuit
     *
     * @return StudentQuitResource
     *
     * @throws UpdateResourceFailedException
     */
    #[ResponseFromApiResource(StudentQuitResource::class, Quit::class, Response::HTTP_OK)]
    public function update(StudentQuit $studentQuit, UpdateStudentQuitRequest $request): StudentQuitResource
    {
        $command = UpdateStudentQuitDTOFactory::make($request, $studentQuit->id);

        return new StudentQuitResource($this->studentQuitService->update($command));
    }

    /**
     * Delete quit session
     *
     * This endpoint allows student to delete a graduation ceremony.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param StudentQuit $studentQuit
     * @param DeleteStudentQuitRequest $request
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @throws DeleteResourceFailedException
     * @response 204 Indicates that the response will be a 204 No Content status.
     *
     */
    public function destroy(StudentQuit $studentQuit, DeleteStudentQuitRequest $request): JsonResponse
    {
        $this->studentQuitService->delete($studentQuit->id);

        return $this->noContent();
    }

    /**
     * Import student quit
     *
     * This endpoint allows student to import a student graduate
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @throws CreateResourceFailedException
     *
     * @response 204
     */
    public function importStudent(ImportStudentQuitRequest $request): JsonResponse
    {
        $command = ImportStudentQuitDTOFactory::make($request);

        $this->studentQuitService->importStudentQuit($command);

        return $this->noContent();
    }

    /**
     * Download template import
     *
     * This endpoint allows student to download file student quit import
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @response 200
     *
     * @throws AuthorizationException
     */
    public function downloadTemplateImport(): BinaryFileResponse
    {
        $this->authorize('admin.quit.import');

        $file = public_path() . '/template/template_student_quit.xlsx';

        return response()->download($file, 'template-student-quit.xlsx');
    }
}
