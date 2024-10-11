<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\DeleteResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Factories\Graduation\CreateGraduationDTOFactory;
use App\Factories\Graduation\ImportStudentGraduateDTOFactory;
use App\Factories\Graduation\ListGraduationDTOFactory;
use App\Factories\Graduation\UpdateGraduationDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Graduation\DeleteGraduationRequest;
use App\Http\Requests\Admin\Graduation\ImportStudentGraduateRequest;
use App\Http\Requests\Admin\Graduation\ListGraduationRequest;
use App\Http\Requests\Admin\Graduation\ShowGraduationRequest;
use App\Http\Requests\Admin\Graduation\StoreGraduationRequest;
use App\Http\Requests\Admin\Graduation\UpdateGraduationRequest;
use App\Http\Resources\Graduation\GraduationCeremonyCollection;
use App\Http\Resources\Graduation\GraduationCeremonyResource;
use App\Models\ExcelImportFile;
use App\Models\GraduationCeremony;
use App\Services\ExcelImportFile\ExcelImportFileService;
use App\Services\Graduation\GraduationService;
use App\Supports\Constants;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Graduation
 *
 * @subgroupDescription APIs for Graduation
 */
class GraduationController extends Controller
{
    public function __construct(
        private readonly GraduationService $graduationService,
        private readonly ExcelImportFileService $excelImportFileService,
    ) {
    }

    /**
     * List of graduation ceremony
     *
     * This endpoint lets you view a list of graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param ListGraduationRequest $request
     * @return GraduationCeremonyCollection Returns the list of posts.
     */
    #[ResponseFromApiResource(GraduationCeremonyCollection::class, GraduationCeremony::class, Response::HTTP_OK, paginate: Constants::PAGE_LIMIT)]
    public function index(ListGraduationRequest $request): GraduationCeremonyCollection
    {
        $command = ListGraduationDTOFactory::make($request);

        return new GraduationCeremonyCollection($this->graduationService->getList($command));
    }


    /**
     * Create graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param StoreGraduationRequest $request
     *
     * @return GraduationCeremonyResource
     * @throws CreateResourceFailedException
     */
    #[ResponseFromApiResource(GraduationCeremonyResource::class, GraduationCeremony::class, Response::HTTP_CREATED)]
    public function store(StoreGraduationRequest $request): GraduationCeremonyResource
    {
        $command = CreateGraduationDTOFactory::make($request);

        return new GraduationCeremonyResource($this->graduationService->create($command));

    }

    /**
     * Show graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param ShowGraduationRequest $request
     * @param GraduationCeremony $graduationCeremony
     *
     * @return GraduationCeremonyResource
     */
    #[ResponseFromApiResource(GraduationCeremonyResource::class, GraduationCeremony::class, Response::HTTP_OK)]
    public function show(ShowGraduationRequest $request, GraduationCeremony $graduationCeremony): GraduationCeremonyResource
    {
        return new GraduationCeremonyResource($graduationCeremony);

    }

    /**
     * Update graduation ceremony
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param UpdateGraduationRequest $request
     * @param GraduationCeremony $graduationCeremony
     *
     * @return GraduationCeremonyResource
     *
     * @throws UpdateResourceFailedException
     */
    #[ResponseFromApiResource(GraduationCeremonyResource::class, GraduationCeremony::class, Response::HTTP_OK)]
    public function update(UpdateGraduationRequest $request, GraduationCeremony $graduationCeremony): GraduationCeremonyResource
    {
        $command = UpdateGraduationDTOFactory::make($request, $graduationCeremony->id);

        $graduation = $this->graduationService->update($command);

        return new GraduationCeremonyResource($graduation);
    }

    /**
     * Import student graduate
     *
     * This endpoint allows student to import a student graduate
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @throws CreateResourceFailedException
     *
     * @response 204
     */
    public function importStudent(ImportStudentGraduateRequest $request): JsonResponse
    {
        $command = ImportStudentGraduateDTOFactory::make($request);

        $this->graduationService->importStudentGraduate($command);

        return $this->noContent();
    }

    /**
     * Download template import
     *
     * This endpoint allows student to download file student.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @response 200
     *
     * @throws AuthorizationException
     */
    public function downloadTemplateImport(): BinaryFileResponse
    {
        $this->authorize('admin.graduation.import');

        $file = public_path() . '/template/template_student_graduated.xlsx';

        return response()->download($file, 'template-student-graduated.xlsx');
    }

    /**
     * Delete Graduation Ceremony
     *
     * This endpoint allows student to delete a graduation ceremony.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param GraduationCeremony $graduationCeremony The graduation ceremony entity to be deleted.
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     *
     * @throws DeleteResourceFailedException
     */
    public function destroy(DeleteGraduationRequest $request, GraduationCeremony $graduationCeremony): JsonResponse
    {
        $this->graduationService->delete($graduationCeremony);

        return $this->noContent();
    }

    /**
     * Download error import course
     *
     * This endpoint allows student to download file student.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param ExcelImportFile $excelImportFileError
     *
     * @return StreamedResponse
     * @throws AuthorizationException
     * @response 200
     *
     */
    public function downloadErrorImportCourse(ExcelImportFile $excelImportFileError): StreamedResponse
    {
        $this->authorize('admin.graduation.import');

        return $this->excelImportFileService->exportErrorRecord($excelImportFileError->id);
    }

}
