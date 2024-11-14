<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\City\CityCollection;
use App\Http\Resources\TrainingIndustry\TrainingIndustryCollection;
use App\Models\TrainingIndustry;
use App\Services\Citi\CitiService;
use App\Supports\Constants;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Student API
 *
 * APIs for student
 *
 * @subgroup TrainingIndustry
 *
 * @subgroupDescription APIs for TrainingIndustry
 */
class CitiController extends Controller
{
    public function __construct(private readonly CitiService $citiService)
    {
    }

    /**
     * List of trainingIndustry
     *
     * This endpoint lets you views list a TrainingIndustry
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing the role ID.
     * @return CityCollection Returns the list of TrainingIndustry.
     */
    #[ResponseFromApiResource(TrainingIndustryCollection::class, TrainingIndustry::class, Response::HTTP_OK, with: [
        'faculty',
    ], paginate: Constants::PAGE_LIMIT)]
    public function index(Request $request): CityCollection
    {
        return new CityCollection($this->citiService->getList($request->all()));
    }
}
