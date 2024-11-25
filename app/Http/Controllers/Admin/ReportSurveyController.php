<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportSurvey\ReportSurveyService;
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
     * Report Survey by year
     *
     * This endpoint lets you views list a ReportSurvey
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing the role ID.
     */
    public function getReportEmploymentSurveyTemplateOne(Request $request): StreamedResponse
    {
        // Create a ListReportSurveyDTOFactory object using the provided request

        return $this->reportSurveyService->getReportEmploymentSurveyTemplateOne($request->survey_id);
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
    public function getReportEmploymentSurveyTemplateThree(Request $request): StreamedResponse
    {
        // Create a ListReportSurveyDTOFactory object using the provided request

        return $this->reportSurveyService->getReportEmploymentSurveyTemplateThree($request->survey_id);

        // The ReportSurveyCollection may format the data as needed before sending it as a response
        //        return $this->noContent();
    }
}
