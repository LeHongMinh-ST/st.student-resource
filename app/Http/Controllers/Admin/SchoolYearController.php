<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Factories\SchoolYear\ListSchoolYearDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SchoolYear\ListSchoolYearRequest;
use App\Http\Resources\SchoolYear\SchoolYearCollection;
use App\Models\SchoolYear;
use App\Services\SchoolYear\SchoolYearService;
use App\Supports\Constants;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup School Year
 *
 * @subgroupDescription APIs for admin
 */
class SchoolYearController extends Controller
{
    public function __construct(
        private readonly SchoolYearService $schoolYearService,
    ) {
    }

    /**
     * List of School Year
     *
     * This endpoint lets you views list AdmissionYear
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param ListSchoolYearRequest $request
     *
     * @return SchoolYearCollection Returns the list of admission year.
     */
    #[ResponseFromApiResource(SchoolYearCollection::class, SchoolYear::class, Response::HTTP_OK, paginate: Constants::PAGE_LIMIT)]
    public function index(ListSchoolYearRequest $request): SchoolYearCollection
    {
        $schoolYearDto = ListSchoolYearDTOFactory::make($request);


        return new SchoolYearCollection($this->schoolYearService->getList($schoolYearDto));
    }
}
