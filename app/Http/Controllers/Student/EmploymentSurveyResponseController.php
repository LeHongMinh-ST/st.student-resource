<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Factories\EmploymentSurveyResponse\CreateEmploymentSurveyResponseDTOFactory;
use App\Factories\EmploymentSurveyResponse\CreateEmploymentSurveyResponseFromLinkPublicDTOFactory;
use App\Factories\EmploymentSurveyResponse\UpdateEmploymentSurveyResponseDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\EmploymentSurveyResponse\ExternalSearchEmploymentResponseRequest;
use App\Http\Requests\Student\EmploymentSurveyResponse\StoreEmploymentSurveyResponseRequest;
use App\Http\Requests\Student\EmploymentSurveyResponse\UpdateEmploymentSurveyResponseRequest;
use App\Http\Resources\EmploymentSurveyResponse\EmploymentSurveyResponseResource;
use App\Http\Resources\EmploymentSurveyResponse\ExternalEmploymentSurveyResponseResource;
use App\Models\EmploymentSurveyResponse;
use App\Services\EmploymentSurveyResponse\EmploymentSurveyResponseService;
use Illuminate\Validation\ValidationException;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Student API
 *
 * APIs for student
 *
 * @subgroup EmploymentSurveyResponse
 *
 * @subgroupDescription APIs for EmploymentSurveyResponse
 */
class EmploymentSurveyResponseController extends Controller
{
    public function __construct(private readonly EmploymentSurveyResponseService $employmentSurveyResponseService)
    {
    }

    /**
     * search employmentSurveyResponse.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ExternalSearchEmploymentResponseRequest  $request  The HTTP request object containing student data.
     * @return ExternalEmploymentSurveyResponseResource Returns the newly EmploymentSurveyResponseResource as a resource.
     */
    #[ResponseFromApiResource(ExternalEmploymentSurveyResponseResource::class, EmploymentSurveyResponse::class, Response::HTTP_CREATED, with: [
        'faculty',
    ])]
    public function search(ExternalSearchEmploymentResponseRequest $request): ExternalEmploymentSurveyResponseResource
    {
        $employmentSurveyResponse = $this->employmentSurveyResponseService->searchByCode($request->only(['student_code', 'survey_period_id', 'code_verify']));

        // Return a JSON response with the generated token and the student API section
        return new ExternalEmploymentSurveyResponseResource($employmentSurveyResponse);
    }

    /**
     * Create employmentSurveyResponse.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  StoreEmploymentSurveyResponseRequest  $request  The HTTP request object containing student data.
     * @return EmploymentSurveyResponseResource Returns the newly EmploymentSurveyResponseResource as a resource.
     *
     * @throws ValidationException
     */
    #[ResponseFromApiResource(EmploymentSurveyResponseResource::class, EmploymentSurveyResponse::class, Response::HTTP_CREATED, with: [
        'faculty',
    ])]
    public function store(StoreEmploymentSurveyResponseRequest $request): EmploymentSurveyResponseResource
    {
        $createEmploymentSurveyResponseDTO = $request->from_link_mail
            ? CreateEmploymentSurveyResponseDTOFactory::make($request)
            : CreateEmploymentSurveyResponseFromLinkPublicDTOFactory::make($request);

        $employmentSurveyResponse = $this->employmentSurveyResponseService->createOrUpdate($createEmploymentSurveyResponseDTO);

        // Return a JSON response with the generated token and the student API section
        return new EmploymentSurveyResponseResource($employmentSurveyResponse);
    }
    /**
     * Update employmentSurveyResponse.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  UpdateEmploymentSurveyResponseRequest  $request  The HTTP request object containing student data.
     * @return ExternalEmploymentSurveyResponseResource Returns the newly EmploymentSurveyResponseResource as a resource.
     *
     */
    #[ResponseFromApiResource(EmploymentSurveyResponseResource::class, EmploymentSurveyResponse::class, Response::HTTP_CREATED, with: [
        'faculty',
    ])]
    public function update(UpdateEmploymentSurveyResponseRequest $request, mixed $id): ExternalEmploymentSurveyResponseResource
    {
        $response = $this->employmentSurveyResponseService->show($id);
        $createEmploymentSurveyResponseDTO = UpdateEmploymentSurveyResponseDTOFactory::make($request, $response);

        $employmentSurveyResponse = $this->employmentSurveyResponseService->update($createEmploymentSurveyResponseDTO, $id);

        // Return a JSON response with the generated token and the student API section
        return new ExternalEmploymentSurveyResponseResource($employmentSurveyResponse);
    }
}
