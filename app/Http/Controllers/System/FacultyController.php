<?php

declare(strict_types=1);

namespace App\Http\Controllers\System;

use App\Factories\Faculty\ListFacultyDTOFactory;
use App\Faculties\Faculty\CreateFacultyDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Faculty\ListFacultyRequest;
use App\Http\Requests\Faculty\StoreFacultyRequest;
use App\Http\Resources\Faculty\FacultyCollection;
use App\Http\Resources\Faculty\FacultyResource;
use App\Models\Faculty;
use App\Services\Faculty\FacultyService;
use App\Supports\Constants;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group System API
 *
 * APIs for system admin
 *
 * @subgroup Faculty
 *
 * @subgroupDescription APIs for auth system
 */
class FacultyController extends Controller
{
    public function __construct(
        private readonly FacultyService $facultyService
    ) {
    }

    /**
     * List of Faculty
     *
     * This endpoint lets you views list a faculty
     *
     * @authenticated
     *
     * @param ListFacultyRequest $request
     *
     * @return FacultyCollection
     */
    #[ResponseFromApiResource(FacultyCollection::class, Faculty::class, paginate: Constants::PAGE_LIMIT)]
    public function index(ListFacultyRequest $request): FacultyCollection
    {
        $faculties = $this->facultyService->getList(ListFacultyDTOFactory::make($request));

        return new FacultyCollection($faculties);
    }

    /**
     * Create Faculty
     *
     * This endpoint lets you create a faculty
     *
     * @authenticated
     */
    #[ResponseFromApiResource(FacultyResource::class, Faculty::class, Response::HTTP_CREATED)]
    public function store(StoreFacultyRequest $request): FacultyResource
    {
        $facultyDTO = CreateFacultyDTOFactory::make($request);

        $faculty = $this->facultyService->create($facultyDTO);

        // Load the 'admins' relationship for the faculty
        // This assumes that the 'admins' relationship is defined in the Faculty model
        $faculty->load('admins');

        // Wrap the faculty data in a FacultyResource and return it
        // The FacultyResource may format the data as needed before sending it as a response
        return new FacultyResource($faculty);
    }
}
