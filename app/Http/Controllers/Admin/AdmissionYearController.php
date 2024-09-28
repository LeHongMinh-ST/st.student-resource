<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\AdmissionYear\ListStudentImportDTO;
use App\Factories\AdmissionYear\ListAdmissionYearDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdmissionYear\ListAdmissionYearRequest;
use App\Http\Requests\Admin\AdmissionYear\ListStudentImportRequest;
use App\Http\Resources\AdmissionYear\AdmissionYearCollection;
use App\Http\Resources\ExcelImportFile\ExcelImportFileCollection;
use App\Models\AdmissionYear;
use App\Models\ExcelImportFile;
use App\Services\AdmissionYear\AdmissionYearService;
use App\Services\ExcelImportFile\ExcelImportFileService;
use App\Supports\Constants;
use App\Supports\MakeDataHelper;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Admission Year
 *
 * @subgroupDescription APIs for admin
 */
class AdmissionYearController extends Controller
{
    public function __construct(
        private readonly AdmissionYearService $admissionYearService,
        private readonly ExcelImportFileService $excelImportFileService,
    ) {
    }

    /**
     * List of admission-year
     *
     * This endpoint lets you views list AdmissionYear
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     */
    #[ResponseFromApiResource(AdmissionYearCollection::class, AdmissionYear::class, Response::HTTP_OK, paginate: Constants::PAGE_LIMIT)]
    public function index(ListAdmissionYearRequest $request): AdmissionYearCollection
    {
        $admissionYearDto = ListAdmissionYearDTOFactory::make($request);
        $admissionYears = $this->admissionYearService->getList($admissionYearDto);

        return new AdmissionYearCollection($admissionYears);
    }

    /**
     * List of student excel file import
     *
     * This endpoint lets you views list a student excel file import
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @return ExcelImportFileCollection Returns the list of ExcelFileImport.
     */
    #[ResponseFromApiResource(
        ExcelImportFileCollection::class,
        ExcelImportFile::class,
        Response::HTTP_OK,
        with: ['user'],
        paginate: Constants::PAGE_LIMIT
    )]
    public function getListStudentFileImports(AdmissionYear $admissionYear, ListStudentImportRequest $request): ExcelImportFileCollection
    {
        $paramsDTO = MakeDataHelper::makeListData($request, new ListStudentImportDTO());
        $paramsDTO->setAdmissionYearId($admissionYear->id);

        $excelFiles = $this->excelImportFileService->getListStudentFileImports($paramsDTO);

        return new ExcelImportFileCollection($excelFiles);
    }
}
