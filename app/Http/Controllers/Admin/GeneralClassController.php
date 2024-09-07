<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Factories\GeneralClass\CreateGeneralClassDTOFactory;
use App\Factories\GeneralClass\ListGeneralClassDTOFactory;
use App\Factories\GeneralClass\UpdateGeneralClassDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralClass\ListGeneralClassRequest;
use App\Http\Requests\GeneralClass\StoreGeneralClassRequest;
use App\Http\Requests\GeneralClass\UpdateGeneralClassRequest;
use App\Http\Resources\GeneralClass\GeneralClassCollection;
use App\Http\Resources\GeneralClass\GeneralClassResource;
use App\Models\GeneralClass;
use App\Services\GeneralClass\GeneralClassService;
use App\Supports\Constants;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Class
 *
 * @subgroupDescription APIs for Class admin
 */
class GeneralClassController extends Controller
{
    public function __construct(
        private readonly GeneralClassService $generalClassService
    ) {}

    /**
     * List of class
     *
     * This endpoint lets you views list a GeneralClass
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListGeneralClassRequest  $request  The HTTP request object containing the role ID.
     * @return GeneralClassCollection Returns the list of GeneralClass.
     *
     */
    #[ResponseFromApiResource(GeneralClassCollection::class, GeneralClass::class, Response::HTTP_OK, with: [
        'teacher', 'faculty',
    ], paginate: Constants::PAGE_LIMIT)]
    public function index(ListGeneralClassRequest $request): GeneralClassCollection
    {
        // Create a ListGeneralClassDTOFactory object using the provided request
        $command = ListGeneralClassDTOFactory::make($request);

        // Wrap the departments data in a GeneralClassCollection and return it
        // The GeneralClassCollection may format the data as needed before sending it as a response
        return new GeneralClassCollection($this->generalClassService->getList($command));
    }

    /**
     * Create class.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  StoreGeneralClassRequest  $request  The HTTP request object containing student data.
     * @return GeneralClassResource Returns the newly GeneralClassResource as a resource.
     */
    #[ResponseFromApiResource(GeneralClassResource::class, GeneralClass::class, Response::HTTP_CREATED, with: [
        'teacher', 'faculty',
    ])]
    public function store(StoreGeneralClassRequest $request): GeneralClassResource
    {
        // Create an CreateUserCommand object using the request data
        $createGeneralClassDTO = CreateGeneralClassDTOFactory::make($request);

        // Create a new generalClass
        $generalClass = $this->generalClassService->create($createGeneralClassDTO);

        // Return a JSON response with the generated token and the admin API section
        return new GeneralClassResource($generalClass);
    }

    /**
     * Update class.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  UpdateGeneralClassRequest  $request  The HTTP request object containing student data.
     * @return GeneralClassResource Returns the newly GeneralClassResource as a resource.
     */
    #[ResponseFromApiResource(GeneralClassResource::class, GeneralClass::class, Response::HTTP_OK, with: [
        'teacher', 'faculty',
    ])]
    public function update(GeneralClass $generalClass, UpdateGeneralClassRequest $request): GeneralClassResource
    {
        // Create an CreateUserCommand object using the request data
        $updateGeneralClassDTO = UpdateGeneralClassDTOFactory::make($request, $generalClass);

        // Update a generalClass
        $generalClass = $this->generalClassService->update($updateGeneralClassDTO);

        // Return a JSON response with the generated token and the admin API section
        return new GeneralClassResource($generalClass);
    }

    /**
     * Delete class
     *
     * This endpoint allows generalClasses to delete a generalClass.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  GeneralClass  $generalClass  The generalClass entity to be deleted.
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     */
    public function destroy(Request $request, GeneralClass $generalClass): JsonResponse
    {
        // Delete the generalClass
        $this->generalClassService->delete($generalClass);

        // Return a JSON response with no content (HTTP 204 status)
        return $this->noContent();
    }
}
