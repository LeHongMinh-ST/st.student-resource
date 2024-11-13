<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurveyPeriod\SurveyPeriodResource;
use App\Models\SurveyPeriod;
use App\Services\SurveyPeriod\SurveyPeriodService;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Student API
 *
 * APIs for admin
 *
 * @subgroup SurveyPeriod
 *
 * @subgroupDescription APIs for SurveyPeriod
 */
class SurveyPeriodController extends Controller
{
    public function __construct(private readonly SurveyPeriodService $surveyPeriodService)
    {
    }

    /**
     * Show surveyPeriod.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing student data.
     * @return SurveyPeriodResource Returns the newly SurveyPeriodResource as a resource.
     *
     */
    #[ResponseFromApiResource(SurveyPeriodResource::class, SurveyPeriod::class, Response::HTTP_OK, with: [
        'faculty',
    ])]
    public function show(SurveyPeriod $surveyPeriod, Request $request): SurveyPeriodResource
    {
        return new SurveyPeriodResource($this->surveyPeriodService->show($surveyPeriod));
    }
}
