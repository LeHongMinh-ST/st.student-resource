<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Factories\User\CreateUserDTOFactory;
use App\Factories\User\ListUserDTOFactory;
use App\Factories\User\UpdateUserDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ListUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use App\Supports\Constants;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup User
 *
 * @subgroupDescription APIs for admin
 */
class UserController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    /**
     * List of user
     *
     * This endpoint lets you views list a User
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListUserRequest  $request  The HTTP request object containing the role ID.
     * @return UserCollection Returns the list of GeneralClass.
     */
    #[ResponseFromApiResource(UserCollection::class, User::class, Response::HTTP_OK, with: [
        'faculty',
    ], paginate: Constants::PAGE_LIMIT)]
    public function index(ListUserRequest $request): UserCollection
    {
        // Create a ListGeneralClassDTOFactory object using the provided request
        $command = ListUserDTOFactory::make($request);

        // Wrap the departments data in a GeneralClassCollection and return it
        // The GeneralClassCollection may format the data as needed before sending it as a response
        return new UserCollection($this->userService->getList($command));
    }

    /**
     * Create user
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  StoreUserRequest  $request  The HTTP request object containing student data.
     * @return UserResource Returns the newly GeneralClassResource as a resource.
     */
    #[ResponseFromApiResource(UserResource::class, User::class, Response::HTTP_CREATED, with: [
        'faculty',
    ])]
    public function store(StoreUserRequest $request): UserResource
    {
        // Create an CreateUserCommand object using the request data
        $createGeneralClassDTO = CreateUserDTOFactory::make($request);

        // Create a new user
        $user = $this->userService->create($createGeneralClassDTO);

        // Return a JSON response with the generated token and the admin API section
        return new UserResource($user);
    }

    /**
     * Update user
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  UpdateUserRequest  $request  The HTTP request object containing student data.
     * @return UserResource Returns the newly UserResource as a resource.
     */
    #[ResponseFromApiResource(UserResource::class, User::class, Response::HTTP_OK, with: [
        'faculty',
    ])]
    public function update(User $user, UpdateUserRequest $request): UserResource
    {
        // Create an UpdateUserDTOFactory object using the request data
        $updateGeneralClassDTO = UpdateUserDTOFactory::make($request, $user);

        // Update a user
        $user = $this->userService->update($updateGeneralClassDTO);

        // Return a JSON response with the generated token and the admin API section
        return new UserResource($user);
    }

    /**
     * Show user
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Request  $request  The HTTP request object containing student data.
     * @return UserResource Returns the newly UserResource as a resource.
     *
     * @throws AuthorizationException
     */
    #[ResponseFromApiResource(UserResource::class, User::class, Response::HTTP_OK, with: [
        'faculty',
    ])]
    public function show(User $user, Request $request): UserResource
    {
        $this->authorize('admin.user.index');

        // Return a JSON response with the generated token and the admin API section
        return new UserResource($user);
    }

    /**
     * Delete class
     *
     * This endpoint allows useres to delete a user.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  User  $user  The user entity to be deleted.
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     *
     * @throws AuthorizationException
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->authorize('admin.user.delete');
        // Delete the user
        $this->userService->delete($user);

        // Return a JSON response with no content (HTTP 204 status)
        return $this->noContent();
    }
}
