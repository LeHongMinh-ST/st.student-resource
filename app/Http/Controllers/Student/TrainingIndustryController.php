<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Factories\TrainingIndustry\ListTrainingIndustryDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\TrainingIndustry\ExternalListTrainingIndustryRequest;
use App\Http\Resources\TrainingIndustry\TrainingIndustryCollection;
use App\Models\TrainingIndustry;
use App\Services\TrainingIndustry\TrainingIndustryService;
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
class TrainingIndustryController extends Controller
{
    public function __construct(private readonly TrainingIndustryService $trainingIndustryService)
    {
    }

    /**
     * List of trainingIndustry
     *
     * This endpoint lets you views list a TrainingIndustry
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ExternalListTrainingIndustryRequest  $request  The HTTP request object containing the role ID.
     * @return TrainingIndustryCollection Returns the list of TrainingIndustry.
     */
    #[ResponseFromApiResource(TrainingIndustryCollection::class, TrainingIndustry::class, Response::HTTP_OK, with: [
        'faculty',
    ], paginate: Constants::PAGE_LIMIT)]
    public function index(ExternalListTrainingIndustryRequest $request): TrainingIndustryCollection
    {
        // Create a ListTrainingIndustryDTOFactory object using the provided request
        $command = ListTrainingIndustryDTOFactory::make($request);

        // The TrainingIndustryCollection may format the data as needed before sending it as a response
        return new TrainingIndustryCollection($this->trainingIndustryService->getList($command));
    }
}
