<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exceptions\CreateResourceFailedException;
use App\Factories\Graduation\ImportStudentGraduateDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Graduation\DeleteGraduationRequest;
use App\Http\Requests\Admin\Graduation\ImportStudentGraduateRequest;
use App\Http\Requests\Admin\Graduation\ListGraduationRequest;
use App\Http\Requests\Admin\Graduation\ShowGraduationRequest;
use App\Http\Requests\Admin\Graduation\StoreGraduationRequest;
use App\Http\Requests\Admin\Graduation\UpdateGraduationRequest;
use App\Models\ExcelImportFile;
use App\Models\GraduationCeremony;
use App\Services\Graduation\GraduationService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
        private readonly GraduationService $graduationService
    ) {
    }

    public function index(ListGraduationRequest $request): void
    {

    }

    public function store(StoreGraduationRequest $request): void
    {

    }

    public function show(ShowGraduationRequest $request): void
    {

    }

    public function update(UpdateGraduationRequest $request): void
    {

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
     * Download class
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
     */
    public function destroy(DeleteGraduationRequest $request, GraduationCeremony $graduationCeremony): JsonResponse
    {
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
     * @response 200
     *
     * @throws AuthorizationException
     */
    public function downloadErrorImportCourse(ExcelImportFile $excelImportFileError): void
    {
        $this->authorize('admin.graduation.import');
    }

}
