<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\ExcelImportType;
use App\Exceptions\CreateResourceFailedException;
use App\Factories\ExcelImportFile\ImportStudentFileDTOFactory;
use App\Factories\ExcelImportFile\ListExcelImportFileDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExcelImportFile\DownloadExcelImportTemplateFileRequest;
use App\Http\Requests\Admin\ExcelImportFile\ImportStudentFileRequest;
use App\Http\Requests\Admin\ExcelImportFile\ListExcelImportFileRequest;
use App\Http\Resources\ExcelImportFile\ExcelImportFileCollection;
use App\Models\ExcelImportFile;
use App\Services\ExcelImportFile\ExcelImportFileService;
use App\Supports\Constants;
use Exception;
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
 * @subgroup file import
 *
 * @subgroupDescription APIs for admin
 */
class ExcelImportFileController extends Controller
{
    public function __construct(
        private readonly ExcelImportFileService $excelImportFileService,
    ) {
    }

    /**
     * List of file import
     *
     * This endpoint lets you views list AdmissionYear
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @return ExcelImportFileCollection Returns the list of admission year.
     */
    #[ResponseFromApiResource(ExcelImportFileCollection::class, ExcelImportFile::class, Response::HTTP_OK, paginate: Constants::PAGE_LIMIT)]
    public function index(ListExcelImportFileRequest $request): ExcelImportFileCollection
    {
        $listExcelImportFileDTO = ListExcelImportFileDTOFactory::make($request);
        $data = $this->excelImportFileService->getListFileImports($listExcelImportFileDTO);

        return new ExcelImportFileCollection($data);
    }

    /**
     * Download file template import
     *
     * This endpoint lets you views list AdmissionYear
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @return BinaryFileResponse Returns the list of admission year.
     *
     * @throws Exception
     */
    public function downloadTemplateImportFile(DownloadExcelImportTemplateFileRequest $request): BinaryFileResponse
    {
        $fileDownload = $this->excelImportFileService->downloadFileImportTemplate(ExcelImportType::from($request->type));

        return response()->download($fileDownload['path_file'], $fileDownload['name_file']);
    }

    /**
     * Download error import course
     *
     * This endpoint allows student to download file student.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @response 200
     */
    public function downloadErrorImport(ExcelImportFile $excelImportFileError): StreamedResponse
    {
        return $this->excelImportFileService->exportErrorRecord($excelImportFileError->id);
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
    public function importStudent(ImportStudentFileRequest $request): JsonResponse
    {
        $command = ImportStudentFileDTOFactory::make($request);

        $this->excelImportFileService->importStudent($command);

        return $this->noContent();
    }

}
