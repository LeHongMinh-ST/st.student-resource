<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Student\StudentSearchRequest;
use App\Http\Resources\Student\StudentCollection;
use App\Models\Student;
use App\Services\Student\StudentService;
use App\Supports\Constants;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

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
    ) {
    }

    /**
     * search one of students
     *
     * This endpoint lets you views list a Student
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  StudentSearchRequest  $request  The HTTP request object containing the role ID.
     * @return StudentCollection Returns the list of Student.
     */
    #[ResponseFromApiResource(StudentCollection::class, Student::class, Response::HTTP_OK, with: [
        'info', 'faculty', 'families',
    ], paginate: Constants::PAGE_LIMIT)]
    public function search(StudentSearchRequest $request): StudentCollection
    {
        // The StudentCollection may format the data as needed before sending it as a response
        return new StudentCollection($this->studentService->searchOneStudent($request->only(['code', 'email', 'phone_number', 'code_verify'])));
    }
}
