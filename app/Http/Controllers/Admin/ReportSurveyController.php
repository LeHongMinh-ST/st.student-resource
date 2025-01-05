<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportSurvey\ReportSurveyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup ReportSurvey
 *
 * @subgroupDescription APIs for ReportSurvey
 */
class ReportSurveyController extends Controller
{
    public function __construct(private readonly ReportSurveyService $reportSurveyService)
    {
    }

    /**
     * getReportEmploymentSurveyTemplateOne
     *
     * This endpoint lets you views list a ReportSurvey
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing the role ID.
     */
    public function getReportEmploymentSurveyTemplateOne(Request $request): JsonResponse
    {
        // Create a ListReportSurveyDTOFactory object using the provided request

        return $this->json([
            'data' => $this->reportSurveyService->getDataReportEmploymentSurveyTemplateOne($request->survey_id),
        ]);
    }

    /**
     * getReportEmploymentSurveyTemplateTwo
     *
     * This endpoint lets you views list a ReportSurvey
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing the role ID.
     */
    public function getReportEmploymentSurveyTemplateTwo(Request $request): JsonResponse
    {
        // Create a ListReportSurveyDTOFactory object using the provided request

        return $this->json([
            'data' => $this->reportSurveyService->getReportEmploymentSurveyTemplateTwo($request->survey_id)['data'],
        ]);
    }

    /**
     * getReportEmploymentSurveyTemplateThree
     *
     * This endpoint lets you views list a ReportSurvey
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing the role ID.
     */
    public function getReportEmploymentSurveyTemplateThree(Request $request): JsonResponse
    {
        // Create a ListReportSurveyDTOFactory object using the provided request

        return $this->json([
            'data' => $this->reportSurveyService->getDataReportEmploymentSurveyTemplateThree($request->survey_id),
        ]);
    }

    /**
     * downloadReportEmploymentSurveyTemplateOne
     *
     * This endpoint lets you views list a ReportSurvey
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing the role ID.
     */
    public function downloadReportEmploymentSurveyTemplateOne(Request $request): StreamedResponse
    {
        // Create a ListReportSurveyDTOFactory object using the provided request

        return $this->reportSurveyService->downloadReportEmploymentSurveyTemplateOne($request->survey_id);
    }

    /**
     * downloadReportEmploymentSurveyTemplateTwo
     *
     * This endpoint lets you views list a ReportSurvey
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing the role ID.
     */
    public function downloadReportEmploymentSurveyTemplateTwo(Request $request): StreamedResponse
    {
        return $this->reportSurveyService->downloadReportEmploymentSurveyTemplateTwo($request->survey_id);
    }

    /**
     * downloadReportEmploymentSurveyTemplateThree
     *
     * This endpoint lets you views list a ReportSurvey
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing the role ID.
     */
    public function downloadReportEmploymentSurveyTemplateThree(Request $request): StreamedResponse
    {
        // Create a ListReportSurveyDTOFactory object using the provided request

        return $this->reportSurveyService->downloadReportEmploymentSurveyTemplateThree($request->survey_id);
    }
}
