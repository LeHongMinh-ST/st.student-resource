<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Factories\Department\CreateDepartmentDTOFactory;
use App\Factories\Department\ListDepartmentDTOFactory;
use App\Factories\Department\UpdateDepartmentDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Department\ListDepartmentRequest;
use App\Http\Requests\Admin\Department\StoreDepartmentRequest;
use App\Http\Requests\Admin\Department\UpdateDepartmentRequest;
use App\Http\Resources\Department\DepartmentCollection;
use App\Http\Resources\Department\DepartmentResource;
use App\Models\Department;
use App\Services\Department\DepartmentService;
use App\Supports\Constants;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Department
 *
 * @subgroupDescription APIs for Department
 */
class DepartmentController extends Controller
{
    public function __construct(private readonly DepartmentService $departmentService) {}

    /**
     * List of department
     *
     * This endpoint lets you views list a Department
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListDepartmentRequest  $request  The HTTP request object containing the role ID.
     * @return DepartmentCollection Returns the list of Department.
     */
    #[ResponseFromApiResource(DepartmentCollection::class, Department::class, Response::HTTP_OK, with: [
        'faculty',
    ], paginate: Constants::PAGE_LIMIT)]
    public function index(ListDepartmentRequest $request): DepartmentCollection
    {
        // Create a ListDepartmentDTOFactory object using the provided request
        $command = ListDepartmentDTOFactory::make($request);

        // The DepartmentCollection may format the data as needed before sending it as a response
        return new DepartmentCollection($this->departmentService->getList($command));
    }

    /**
     * Create department.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  StoreDepartmentRequest  $request  The HTTP request object containing student data.
     * @return DepartmentResource Returns the newly DepartmentResource as a resource.
     */
    #[ResponseFromApiResource(DepartmentResource::class, Department::class, Response::HTTP_CREATED, with: [
        'faculty',
    ])]
    public function store(StoreDepartmentRequest $request): DepartmentResource
    {
        // Create an CreateUserCommand object using the request data
        $createDepartmentDTO = CreateDepartmentDTOFactory::make($request);

        // Create a new generalClass
        $department = $this->departmentService->create($createDepartmentDTO);

        // Return a JSON response with the generated token and the admin API section
        return new DepartmentResource($department);
    }

    /**
     * Update department.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  UpdateDepartmentRequest  $request  The HTTP request object containing student data.
     * @return DepartmentResource Returns the newly DepartmentResource as a resource.
     */
    #[ResponseFromApiResource(DepartmentResource::class, Department::class, Response::HTTP_OK, with: [
        'faculty',
    ])]
    public function update(Department $department, UpdateDepartmentRequest $request): DepartmentResource
    {
        // Create an CreateUserCommand object using the request data
        $updateDepartmentDTO = UpdateDepartmentDTOFactory::make($request, $department);

        // Update a generalClass
        $department = $this->departmentService->update($updateDepartmentDTO);

        // Return a JSON response with the generated token and the admin API section
        return new DepartmentResource($department);
    }

    /**
     * Delete department
     *
     * This endpoint allows generalClasses to delete a generalClass.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Department  $department  The generalClass entity to be deleted.
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @throws AuthorizationException|ValidationException
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     */
    public function destroy(Request $request, Department $department): JsonResponse
    {
        $this->authorize('admin.department.destroy');
        // Delete the generalClass
        $this->departmentService->delete($department);

        // Return a JSON response with no content (HTTP 204 status)
        return $this->noContent();
    }

    /**
     * Show department.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing student data.
     * @return DepartmentResource Returns the newly DepartmentResource as a resource.
     *
     * @throws AuthorizationException
     */
    #[ResponseFromApiResource(DepartmentResource::class, Department::class, Response::HTTP_OK, with: [
        'faculty',
    ])]
    public function show(Department $department, Request $request): DepartmentResource
    {
        $this->authorize('admin.department.index');

        return new DepartmentResource($department);
    }
}
