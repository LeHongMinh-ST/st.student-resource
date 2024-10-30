<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Factories\TrainingIndustry\CreateTrainingIndustryDTOFactory;
use App\Factories\TrainingIndustry\ListTrainingIndustryDTOFactory;
use App\Factories\TrainingIndustry\UpdateTrainingIndustryDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TrainingIndustry\ListTrainingIndustryRequest;
use App\Http\Requests\Admin\TrainingIndustry\StoreTrainingIndustryRequest;
use App\Http\Requests\Admin\TrainingIndustry\UpdateTrainingIndustryRequest;
use App\Http\Resources\TrainingIndustry\TrainingIndustryCollection;
use App\Http\Resources\TrainingIndustry\TrainingIndustryResource;
use App\Models\TrainingIndustry;
use App\Services\TrainingIndustry\TrainingIndustryService;
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
 * @subgroup TrainingIndustry
 *
 * @subgroupDescription APIs for TrainingIndustry
 */
class TrainingIndustryController extends Controller
{
    public function __construct(private readonly TrainingIndustryService $trainingIndustryService) {}

    /**
     * List of trainingIndustry
     *
     * This endpoint lets you views list a TrainingIndustry
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListTrainingIndustryRequest  $request  The HTTP request object containing the role ID.
     * @return TrainingIndustryCollection Returns the list of TrainingIndustry.
     */
    #[ResponseFromApiResource(TrainingIndustryCollection::class, TrainingIndustry::class, Response::HTTP_OK, with: [
        'faculty',
    ], paginate: Constants::PAGE_LIMIT)]
    public function index(ListTrainingIndustryRequest $request): TrainingIndustryCollection
    {
        // Create a ListTrainingIndustryDTOFactory object using the provided request
        $command = ListTrainingIndustryDTOFactory::make($request);

        // The TrainingIndustryCollection may format the data as needed before sending it as a response
        return new TrainingIndustryCollection($this->trainingIndustryService->getList($command));
    }

    /**
     * Create trainingIndustry.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  StoreTrainingIndustryRequest  $request  The HTTP request object containing student data.
     * @return TrainingIndustryResource Returns the newly TrainingIndustryResource as a resource.
     */
    #[ResponseFromApiResource(TrainingIndustryResource::class, TrainingIndustry::class, Response::HTTP_CREATED, with: [
        'faculty',
    ])]
    public function store(StoreTrainingIndustryRequest $request): TrainingIndustryResource
    {
        $createTrainingIndustryDTO = CreateTrainingIndustryDTOFactory::make($request);

        $trainingIndustry = $this->trainingIndustryService->create($createTrainingIndustryDTO);

        // Return a JSON response with the generated token and the admin API section
        return new TrainingIndustryResource($trainingIndustry);
    }

    /**
     * Update trainingIndustry.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  UpdateTrainingIndustryRequest  $request  The HTTP request object containing student data.
     * @return TrainingIndustryResource Returns the newly TrainingIndustryResource as a resource.
     */
    #[ResponseFromApiResource(TrainingIndustryResource::class, TrainingIndustry::class, Response::HTTP_OK, with: [
        'faculty',
    ])]
    public function update(TrainingIndustry $trainingIndustry, UpdateTrainingIndustryRequest $request): TrainingIndustryResource
    {
        $updateTrainingIndustryDTO = UpdateTrainingIndustryDTOFactory::make($request, $trainingIndustry);

        $trainingIndustry = $this->trainingIndustryService->update($updateTrainingIndustryDTO);

        // Return a JSON response with the generated token and the admin API section
        return new TrainingIndustryResource($trainingIndustry);
    }

    /**
     * Delete trainingIndustry
     *
     * This endpoint allows generalClasses to delete a generalClass.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  TrainingIndustry  $trainingIndustry  The generalClass entity to be deleted.
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @throws AuthorizationException
     * @throws ValidationException
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     */
    public function destroy(Request $request, TrainingIndustry $trainingIndustry): JsonResponse
    {
        $this->authorize('admin.training-industry.destroy');
        $this->trainingIndustryService->delete($trainingIndustry->id);

        // Return a JSON response with no content (HTTP 204 status)
        return $this->noContent();
    }

    /**
     * Show trainingIndustry.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing student data.
     * @return TrainingIndustryResource Returns the newly TrainingIndustryResource as a resource.
     *
     * @throws AuthorizationException
     */
    #[ResponseFromApiResource(TrainingIndustryResource::class, TrainingIndustry::class, Response::HTTP_OK, with: [
        'faculty',
    ])]
    public function show(TrainingIndustry $trainingIndustry, Request $request): TrainingIndustryResource
    {
        $this->authorize('admin.training-industry.index');

        return new TrainingIndustryResource($trainingIndustry);
    }
}
