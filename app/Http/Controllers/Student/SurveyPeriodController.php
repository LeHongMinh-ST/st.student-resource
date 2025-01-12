<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\EmploymentSurveyResponse\VerifyInfoStudentResponseRequest;
use App\Http\Resources\SurveyPeriod\ExternalSurveyPeriodResource;
use App\Http\Resources\SurveyPeriod\SurveyPeriodResource;
use App\Models\SurveyPeriod;
use App\Services\SurveyPeriod\SurveyPeriodService;
use Illuminate\Http\JsonResponse;
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
     * @return ExternalSurveyPeriodResource Returns the newly SurveyPeriodResource as a resource.
     */
    #[ResponseFromApiResource(SurveyPeriodResource::class, SurveyPeriod::class, Response::HTTP_OK, with: [
        'faculty',
    ])]
    public function show(SurveyPeriod $surveyPeriod, Request $request): ExternalSurveyPeriodResource
    {
        return new ExternalSurveyPeriodResource($this->surveyPeriodService->show($surveyPeriod->id));
    }

    /**
     * verify Info Student.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  VerifyInfoStudentResponseRequest  $request  The HTTP request object containing student data.
     * @return JsonResponse Returns the newly SurveyPeriodResource as a resource.
     */
    #[ResponseFromApiResource(SurveyPeriodResource::class, SurveyPeriod::class, Response::HTTP_OK, with: [
        'faculty',
    ])]
    public function verifyInfoStudent(mixed $id, VerifyInfoStudentResponseRequest $request): JsonResponse
    {
        $studentData = $this->surveyPeriodService->verifyInfoStudent($id, $request->all());
        return $this->json([
            'data' => $studentData,
        ]);
    }
}
