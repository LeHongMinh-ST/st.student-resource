<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\AdmissionYear\ListStudentImportDTO;
use App\Enums\ClassType;
use App\Factories\AdmissionYear\ListAdmissionYearDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdmissionYear\ListAdmissionYearRequest;
use App\Http\Requests\Admin\AdmissionYear\ListStudentImportRequest;
use App\Http\Requests\Admin\AdmissionYear\ShowStatisticalAdmissionYear;
use App\Http\Resources\AdmissionYear\AdmissionYearCollection;
use App\Http\Resources\ExcelImportFile\ExcelImportFileCollection;
use App\Models\AdmissionYear;
use App\Models\ExcelImportFile;
use App\Models\Student;
use App\Services\AdmissionYear\AdmissionYearService;
use App\Services\ExcelImportFile\ExcelImportFileService;
use App\Services\Student\StudentService;
use App\Supports\Constants;
use App\Supports\MakeDataHelper;
use Illuminate\Http\JsonResponse;
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
        private readonly StudentService $studentService,
    ) {
    }

    /**
     * List of Admission Year
     *
     * This endpoint lets you views list AdmissionYear
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param ListAdmissionYearRequest $request
     *
     * @return AdmissionYearCollection Returns the list of admission year.
     */
    #[ResponseFromApiResource(AdmissionYearCollection::class, AdmissionYear::class, Response::HTTP_OK, paginate: Constants::PAGE_LIMIT)]
    public function index(ListAdmissionYearRequest $request): AdmissionYearCollection
    {
        $admissionYearDto = ListAdmissionYearDTOFactory::make($request);

        return new AdmissionYearCollection($this->admissionYearService->getList($admissionYearDto));
    }

    public function getStatisticalClassAdmissionYear(AdmissionYear $admissionYear): JsonResponse
    {
        return $this->json([
            'class_total' => $admissionYear->generalClasses()->count(),
            'class_basic_total' => $admissionYear->generalClasses()->where('type', ClassType::Basic)->count(),
            'class_major_none_total' => $admissionYear->generalClasses()->where('type', ClassType::Major)->whereNull(
                'training_industry_id'
            )->count()
        ]);
    }

    public function getStatisticalAdmissionYear(AdmissionYear $admissionYear, ShowStatisticalAdmissionYear $showAdmissionYearRequest): JsonResponse
    {
        $admissionYearId = $admissionYear->id;
        return $this->json([
            'total' => Student::query()->where('admission_year_id', $admissionYearId)->count(),
            'graduated' => $this->studentService->getTotalStudentGraduatedByAdmissionYearId($admissionYearId),
            'to_drop_out' => $this->studentService->getTotalStudentToDropOutByAdmissionYearId($admissionYearId),
            'quit' => $this->studentService->getTotalStudentQuitByAdmissionYearId($admissionYearId),
            'study' => $this->studentService->getTotalStudentStudyByAdmissionYearId($admissionYearId),
            'transfer_study' => $this->studentService->getTotalStudentTransferStudyByAdmissionYearId($admissionYearId),
            'deferred' => $this->studentService->getTotalStudentDeferredByAdmissionYearId($admissionYearId),
            'warning' => $this->studentService->getTotalStudentWarningByAdmissionId($admissionYearId)
        ]);
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


    public function getTrainingIndustryClass(AdmissionYear $admissionYear)
    {
        return $this->json($this->admissionYearService->getTrainingIndustryClassByAdmissionId($admissionYear));
    }
}
