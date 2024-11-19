<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Factories\Student\CreateStudentDTOFactory;
use App\Factories\Student\ImportCourseStudentDTOFactory;
use App\Factories\Student\ListStudentDTOFactory;
use App\Factories\Student\ListStudentSurveyDTOFactory;
use App\Factories\Student\UpdateStudentDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Student\CreateStudentRequest;
use App\Http\Requests\Admin\Student\ImportCourseStudentRequest;
use App\Http\Requests\Admin\Student\ListStudentRequest;
use App\Http\Requests\Admin\Student\ShowStudentRequest;
use App\Http\Requests\Admin\Student\UpdateStudentRequest;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\Student\StudentResource;
use App\Http\Resources\Student\StudentSurveyCollection;
use App\Models\ExcelImportFile;
use App\Models\Student;
use App\Services\ExcelImportFile\ExcelImportFileService;
use App\Services\Student\StudentService;
use App\Supports\Constants;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Response as ResponseApi;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Student
 *
 * @subgroupDescription APIs for admin
 */
class StudentController extends Controller
{
    public function __construct(
        private readonly StudentService         $studentService,
        private readonly ExcelImportFileService $excelImportFileService,
    ) {
    }

    /**
     * List of student
     *
     * This endpoint lets you views list a Student
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListStudentRequest  $request  The HTTP request object containing the role ID.
     * @return StudentCollection Returns the list of Student.
     */
    #[ResponseFromApiResource(StudentCollection::class, Student::class, Response::HTTP_OK, with: [
        'info', 'faculty', 'families',
    ], paginate: Constants::PAGE_LIMIT)]
    public function index(ListStudentRequest $request): StudentCollection
    {
        // Create a ListStudentDTOFactory object using the provided request
        $command = ListStudentDTOFactory::make($request);

        // The StudentCollection may format the data as needed before sending it as a response
        return new StudentCollection($this->studentService->getList($command));
    }

    /**
     * List of student By Survey Period
     *
     * This endpoint lets you views list a Student
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListStudentRequest  $request  The HTTP request object containing the role ID.
     * @return StudentSurveyCollection Returns the list of Student.
     */
    #[ResponseFromApiResource(StudentSurveyCollection::class, Student::class, Response::HTTP_OK, with: [
        'info', 'surveyPeriods',
    ], paginate: Constants::PAGE_LIMIT)]
    public function getBySurveyPeriod(ListStudentRequest $request, mixed $id): StudentSurveyCollection
    {
        // Create a ListStudentDTOFactory object using the provided request
        $command = ListStudentSurveyDTOFactory::make($request, $id);

        // The StudentCollection may format the data as needed before sending it as a response
        return new StudentSurveyCollection($this->studentService->getBySurveyPeriod($command));
    }

    /**
     * Get total student
     *
     * This endpoint lets you views list a Student
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListStudentRequest  $request  The HTTP request object containing the role ID.
     * @return JsonResponse Returns the list of Student.
     */
    #[ResponseApi(content: [
        'total' => 1,
    ], status: 200)]
    public function getTotalStudent(ListStudentRequest $request): JsonResponse
    {
        // Create a ListStudentDTOFactory object using the provided request
        $command = ListStudentDTOFactory::make($request);

        // The StudentCollection may format the data as needed before sending it as a response
        return $this->json(['total' => $this->studentService->getTotalStudent($command)]);
    }

    /**
     * Create student
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  CreateStudentRequest  $request  The HTTP request object containing student data.
     * @return StudentResource Returns the newly StudentResource as a resource.
     *
     * @throws CreateResourceFailedException
     */
    #[ResponseFromApiResource(StudentResource::class, Student::class, Response::HTTP_CREATED, with: [
        'info', 'faculty', 'families',
    ])]
    public function store(CreateStudentRequest $request): StudentResource
    {
        // Create an CreateUserCommand object using the request data
        $createStudentDTO = CreateStudentDTOFactory::make($request);

        // Create a new user
        $user = $this->studentService->createWithInfoStudent($createStudentDTO);

        $user->load(['info', 'faculty', 'families']);

        // Return a JSON response with the generated token and the admin API section
        return new StudentResource($user);
    }

    /**
     * Show student
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ShowStudentRequest  $request  The HTTP request object containing student data.
     * @return StudentResource Returns the newly UserResource as a resource.
     */
    #[ResponseFromApiResource(StudentResource::class, Student::class, Response::HTTP_OK, with: [
        'info', 'faculty', 'families', 'currentClass'
    ])]
    public function show(Student $student, ShowStudentRequest $request): StudentResource
    {
        $student->load(['info', 'faculty', 'families', 'currentClass']);

        // Return a JSON response with the generated token and the admin API section
        return new StudentResource($student);
    }

    /**
     * Update student
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  UpdateStudentRequest  $request  The HTTP request object containing student data.
     * @return StudentResource Returns the newly UserResource as a resource.
     *
     * @throws UpdateResourceFailedException
     */
    #[ResponseFromApiResource(StudentResource::class, Student::class, Response::HTTP_OK, with: [
        'info', 'faculty', 'families',
    ])]
    public function update(Student $student, UpdateStudentRequest $request): StudentResource
    {
        // Create an UpdateUserDTOFactory object using the request data
        $updateStudentDTO = UpdateStudentDTOFactory::make($request, $student);

        // Update a user
        $user = $this->studentService->updateWithInfoStudent($updateStudentDTO);

        // Return a JSON response with the generated token and the admin API section
        return new StudentResource($user);
    }

    /**
     * Delete student
     *
     * This endpoint allows student to delete a student.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Student  $student  The student entity to be deleted.
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     *
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Student $student): JsonResponse
    {
        $this->authorize('admin.student.destroy');
        // Delete the user
        $this->studentService->delete($student);

        // Return a JSON response with no content (HTTP 204 status)
        return $this->noContent();
    }

    /**
     * Import student course
     *
     * This endpoint allows student to import a student.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @throws CreateResourceFailedException
     *
     * @response 204
     */
    public function importCourse(ImportCourseStudentRequest $request): JsonResponse
    {
        // Create a command object from the request
        $command = ImportCourseStudentDTOFactory::make($request);

        // Dispatch the command to import course the student and handle it immediately
        $this->studentService->importCourse($command);

        // Return the newly created student as a resource
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
    public function downloadTemplateImportCourse(): BinaryFileResponse
    {
        $this->authorize('admin.student.import');

        $file = public_path() . '/template/template_course.xlsx';

        return response()->download($file, 'template-course.xlsx');
    }

    /**
     * Download error import course
     *
     * This endpoint allows student to download file student.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @throws AuthorizationException
     *
     * @response 200
     */
    public function downloadErrorImportCourse(ExcelImportFile $excelImportFileError): StreamedResponse
    {
        $this->authorize('admin.student.import');

        return $this->excelImportFileService->exportErrorRecord($excelImportFileError->id);
    }
}
