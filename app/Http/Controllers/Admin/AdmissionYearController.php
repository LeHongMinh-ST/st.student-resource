<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Factories\AdmissionYear\ListAdmissionYearDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdmissionYear\ListAdmissionYearRequest;
use App\Http\Resources\AdmissionYear\AdmissionYearCollection;
use App\Models\AdmissionYear;
use App\Services\AdmissionYear\AdmissionYearService;
use App\Supports\Constants;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Admission Year
 *
 * @subgroupDescription APIs for admin
 */
class AdmissionYearController extends Controller
{
    public function __construct(
        private readonly AdmissionYearService $admissionYearService
    ) {
    }

    /**
     * List of admission year
     *
     * This endpoint lets you views list a Admission Year
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListAdmissionYearRequest  $request  The HTTP request object containing the role ID.
     * @return AdmissionYearCollection Returns the list of GeneralClass.
     */
    #[ResponseFromApiResource(AdmissionYearCollection::class, AdmissionYear::class, Response::HTTP_OK, paginate: Constants::PAGE_LIMIT)]
    public function index(ListAdmissionYearRequest $request): AdmissionYearCollection
    {
        $admissionYearDto = ListAdmissionYearDTOFactory::make($request);
        $admissionYears = $this->admissionYearService->getList($admissionYearDto);

        return new AdmissionYearCollection($admissionYears);
    }
}
