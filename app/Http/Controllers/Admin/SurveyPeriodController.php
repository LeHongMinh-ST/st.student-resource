<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Factories\SurveyPeriod\CreateSurveyPeriodDTOFactory;
use App\Factories\SurveyPeriod\ListSurveyPeriodDTOFactory;
use App\Factories\SurveyPeriod\UpdateSurveyPeriodDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SurveyPeriod\ExportResponseSurveyPeriodRequest;
use App\Http\Requests\Admin\SurveyPeriod\ListSurveyPeriodRequest;
use App\Http\Requests\Admin\SurveyPeriod\SendMailSurveyPeriodRequest;
use App\Http\Requests\Admin\SurveyPeriod\StoreSurveyPeriodRequest;
use App\Http\Requests\Admin\SurveyPeriod\UpdateSurveyPeriodRequest;
use App\Http\Resources\SurveyPeriod\SurveyPeriodCollection;
use App\Http\Resources\SurveyPeriod\SurveyPeriodResource;
use App\Http\Resources\ZipExportFile\ZipExportFileResource;
use App\Models\SurveyPeriod;
use App\Services\SurveyPeriod\SurveyPeriodService;
use App\Supports\Constants;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @group Admin API
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
     * List of surveyPeriod
     *
     * This endpoint lets you views list a SurveyPeriod
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListSurveyPeriodRequest  $request  The HTTP request object containing the role ID.
     * @return SurveyPeriodCollection Returns the list of SurveyPeriod.
     */
    #[ResponseFromApiResource(SurveyPeriodCollection::class, SurveyPeriod::class, Response::HTTP_OK, with: [
        'faculty',
    ], paginate: Constants::PAGE_LIMIT)]
    public function index(ListSurveyPeriodRequest $request): SurveyPeriodCollection
    {
        // Create a ListSurveyPeriodDTOFactory object using the provided request
        $command = ListSurveyPeriodDTOFactory::make($request);

        // The SurveyPeriodCollection may format the data as needed before sending it as a response
        return new SurveyPeriodCollection($this->surveyPeriodService->getList($command));
    }

    /**
     * Create surveyPeriod.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  StoreSurveyPeriodRequest  $request  The HTTP request object containing student data.
     * @return SurveyPeriodResource Returns the newly SurveyPeriodResource as a resource.
     */
    #[ResponseFromApiResource(SurveyPeriodResource::class, SurveyPeriod::class, Response::HTTP_CREATED, with: [
        'faculty',
    ])]
    public function store(StoreSurveyPeriodRequest $request): SurveyPeriodResource
    {
        $createSurveyPeriodDTO = CreateSurveyPeriodDTOFactory::make($request);

        $surveyPeriod = $this->surveyPeriodService->create($createSurveyPeriodDTO);

        // Return a JSON response with the generated token and the admin API section
        return new SurveyPeriodResource($surveyPeriod);
    }

    /**
     * Update surveyPeriod.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  UpdateSurveyPeriodRequest  $request  The HTTP request object containing student data.
     * @return SurveyPeriodResource Returns the newly SurveyPeriodResource as a resource.
     *
     * @throws Exception
     */
    #[ResponseFromApiResource(SurveyPeriodResource::class, SurveyPeriod::class, Response::HTTP_OK, with: [
        'faculty',
    ])]
    public function update(SurveyPeriod $surveyPeriod, UpdateSurveyPeriodRequest $request): SurveyPeriodResource
    {
        $updateSurveyPeriodDTO = UpdateSurveyPeriodDTOFactory::make($request, $surveyPeriod);

        $surveyPeriod = $this->surveyPeriodService->update($updateSurveyPeriodDTO);

        // Return a JSON response with the generated token and the admin API section
        return new SurveyPeriodResource($surveyPeriod);
    }

    /**
     * send mail surveyPeriod
     *
     * This endpoint allows generalClasses to delete a generalClass.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  SurveyPeriod  $surveyPeriod  The generalClass entity to be deleted.
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @throws AuthorizationException|Exception
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     */
    public function sendMails(SurveyPeriod $surveyPeriod, SendMailSurveyPeriodRequest $request): JsonResponse
    {
        $this->surveyPeriodService->sendMail($surveyPeriod, $request->only('student_ids', 'is_all_mail_student'));

        return $this->noContent();
    }

    /**
     * export Response Survey to PDF
     *
     * This endpoint allows generalClasses to delete a generalClass.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  SurveyPeriod  $surveyPeriod  The generalClass entity to be deleted.
     * @return ZipExportFileResource Returns a response with no content upon successful deletion.
     *
     * @throws AuthorizationException|Exception
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     */
    public function exportResponseSurveyToPDF(SurveyPeriod $surveyPeriod, ExportResponseSurveyPeriodRequest $request): ZipExportFileResource
    {
        $zipFile = $this->surveyPeriodService->exportResponseSurveyToPDF($surveyPeriod, $request->only('student_ids', 'is_all_student'));
        $zipFile->load('surveyPeriod');

        return new ZipExportFileResource($zipFile);
    }

    /**
     * downloadZipSurveyResponse
     *
     * This endpoint allows generalClasses to delete a generalClass.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @return StreamedResponse Returns a response with no content upon successful deletion.
     *
     * @throws Exception
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     */
    public function downloadZipSurveyResponse($zipFileId, Request $request): StreamedResponse
    {
        return $this->surveyPeriodService->downloadZipSurveyResponse($zipFileId, $request->only('student_ids', 'is_all_student'));
    }

    /**
     * get Response Survey to PDF
     *
     * This endpoint allows generalClasses to delete a generalClass.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @return ZipExportFileResource Returns a response with no content upon successful deletion.
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     */
    public function getFileZipSurveyResponse($zipFileId, Request $request): ZipExportFileResource
    {
        $zipFile = $this->surveyPeriodService->getFileZipSurveyResponse($zipFileId);
        $zipFile->load('surveyPeriod');

        return new ZipExportFileResource($zipFile);
    }

    /**
     * Delete surveyPeriod
     *
     * This endpoint allows generalClasses to delete a generalClass.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  SurveyPeriod  $surveyPeriod  The generalClass entity to be deleted.
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @throws AuthorizationException
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     */
    public function destroy(Request $request, SurveyPeriod $surveyPeriod): JsonResponse
    {
        $this->authorize('admin.training-industry.destroy');
        $this->surveyPeriodService->delete($surveyPeriod->id);

        // Return a JSON response with no content (HTTP 204 status)
        return $this->noContent();
    }

    /**
     * Show surveyPeriod.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing student data.
     * @return SurveyPeriodResource Returns the newly SurveyPeriodResource as a resource.
     *
     * @throws AuthorizationException
     */
    #[ResponseFromApiResource(SurveyPeriodResource::class, SurveyPeriod::class, Response::HTTP_OK, with: [
        'faculty',
    ])]
    public function show(SurveyPeriod $surveyPeriod, Request $request): SurveyPeriodResource
    {
        $this->authorize('admin.training-industry.index');

        return new SurveyPeriodResource($this->surveyPeriodService->show($surveyPeriod->id));
    }
}
