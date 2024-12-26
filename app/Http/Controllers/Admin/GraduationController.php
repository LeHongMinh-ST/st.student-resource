<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\DeleteResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Factories\Graduation\CreateGraduationDTOFactory;
use App\Factories\Graduation\ListGraduationDTOFactory;
use App\Factories\Graduation\UpdateGraduationDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Graduation\DeleteGraduationRequest;
use App\Http\Requests\Admin\Graduation\ListGraduationRequest;
use App\Http\Requests\Admin\Graduation\ShowGraduationRequest;
use App\Http\Requests\Admin\Graduation\StoreGraduationRequest;
use App\Http\Requests\Admin\Graduation\UpdateGraduationRequest;
use App\Http\Resources\Graduation\GraduationCeremonyCollection;
use App\Http\Resources\Graduation\GraduationCeremonyResource;
use App\Models\GraduationCeremony;
use App\Services\Graduation\GraduationService;
use App\Supports\Constants;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Graduation
 *
 * @subgroupDescription APIs for Graduation
 */
class GraduationController extends Controller
{
    public function __construct(
        private readonly GraduationService $graduationService,
    ) {
    }

    /**
     * List of graduation ceremony
     *
     * This endpoint lets you view a list of graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @return GraduationCeremonyCollection Returns the list of posts.
     */
    #[ResponseFromApiResource(GraduationCeremonyCollection::class, GraduationCeremony::class, Response::HTTP_OK, paginate: Constants::PAGE_LIMIT)]
    public function index(ListGraduationRequest $request): GraduationCeremonyCollection
    {
        $command = ListGraduationDTOFactory::make($request);

        return new GraduationCeremonyCollection($this->graduationService->getList($command));
    }

    /**
     * Create graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @throws CreateResourceFailedException
     */
    #[ResponseFromApiResource(GraduationCeremonyResource::class, GraduationCeremony::class, Response::HTTP_CREATED)]
    public function store(StoreGraduationRequest $request): GraduationCeremonyResource
    {
        $command = CreateGraduationDTOFactory::make($request);

        return new GraduationCeremonyResource($this->graduationService->create($command));

    }

    /**
     * Show graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     */
    #[ResponseFromApiResource(GraduationCeremonyResource::class, GraduationCeremony::class, Response::HTTP_OK)]
    public function show(ShowGraduationRequest $request, GraduationCeremony $graduationCeremony): GraduationCeremonyResource
    {
        return new GraduationCeremonyResource($graduationCeremony->loadCount('students'));

    }

    /**
     * Update graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @throws UpdateResourceFailedException
     */
    #[ResponseFromApiResource(GraduationCeremonyResource::class, GraduationCeremony::class, Response::HTTP_OK)]
    public function update(UpdateGraduationRequest $request, GraduationCeremony $graduationCeremony): GraduationCeremonyResource
    {
        $command = UpdateGraduationDTOFactory::make($request, $graduationCeremony->id);

        $graduation = $this->graduationService->update($command);

        return new GraduationCeremonyResource($graduation);
    }

    /**
     * Delete Graduation Ceremony
     *
     * This endpoint allows student to delete a graduation ceremony.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  GraduationCeremony  $graduationCeremony  The graduation ceremony entity to be deleted.
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     *
     * @throws DeleteResourceFailedException
     */
    public function destroy(DeleteGraduationRequest $request, GraduationCeremony $graduationCeremony): JsonResponse
    {
        $this->graduationService->delete($graduationCeremony);

        return $this->noContent();
    }

}
