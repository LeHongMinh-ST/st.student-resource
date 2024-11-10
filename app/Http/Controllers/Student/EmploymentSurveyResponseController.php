<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Factories\EmploymentSurveyResponse\CreateEmploymentSurveyResponseDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\EmploymentSurveyResponse\StoreEmploymentSurveyResponseRequest;
use App\Http\Resources\EmploymentSurveyResponse\EmploymentSurveyResponseResource;
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
        $createEmploymentSurveyResponseDTO = CreateEmploymentSurveyResponseDTOFactory::make($request);

        $employmentSurveyResponse = $this->employmentSurveyResponseService->createOrUpdate($createEmploymentSurveyResponseDTO);

        // Return a JSON response with the generated token and the student API section
        return new EmploymentSurveyResponseResource($employmentSurveyResponse);
    }
}
