<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\DeleteResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Factories\Post\CreatePostDTOFactory;
use App\Factories\Post\ListPostDTOFactory;
use App\Factories\Post\UpdatePostDTOFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Post\DeletePostRequest;
use App\Http\Requests\Admin\Post\ListPostRequest;
use App\Http\Requests\Admin\Post\ListPublishPostRequest;
use App\Http\Requests\Admin\Post\ShowPostRequest;
use App\Http\Requests\Admin\Post\StorePostRequest;
use App\Http\Requests\Admin\Post\UpdatePostRequest;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Services\Post\PostService;
use App\Supports\Constants;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Post
 *
 * @subgroupDescription APIs for managing posts
 */
class PostController extends Controller
{
    public function __construct(private readonly PostService $postService)
    {
    }

    /**
     * List of posts
     *
     * This endpoint lets you view a list of posts
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListPostRequest $request
     * @return PostCollection Returns the list of posts.
     */
    #[ResponseFromApiResource(PostCollection::class, Post::class, Response::HTTP_OK, paginate: Constants::PAGE_LIMIT)]
    public function index(ListPostRequest $request): PostCollection
    {
        // Create a ListPostDTOFactory object using the provided request
        $command = ListPostDTOFactory::make($request);

        // Wrap the posts data in a PostCollection and return it
        return new PostCollection($this->postService->getList($command));
    }

    /**
     * List of posts
     *
     * This endpoint lets you view a list of posts
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  ListPublishPostRequest $request
     * @return PostCollection Returns the list of posts.
     */
    #[ResponseFromApiResource(PostCollection::class, Post::class, Response::HTTP_OK, paginate: Constants::PAGE_LIMIT)]
    public function getListPostPublish(ListPublishPostRequest $request): PostCollection
    {
        // Create a ListPostDTOFactory object using the provided request
        $command = ListPostDTOFactory::make($request);

        // Wrap the posts data in a PostCollection and return it
        return new PostCollection($this->postService->getList($command));
    }

    /**
     * Create post
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param StorePostRequest $request The HTTP request object containing post data.
     * @return PostResource Returns the newly created PostResource.
     * @throws CreateResourceFailedException
     */
    #[ResponseFromApiResource(PostResource::class, Post::class, Response::HTTP_CREATED)]
    public function store(StorePostRequest $request): PostResource
    {
        // Create a CreatePostDTO object using the request data
        $createPostDTO = CreatePostDTOFactory::make($request);

        // Create a new post
        $post = $this->postService->create($createPostDTO);

        // Return the newly created post as a resource
        return new PostResource($post);
    }

    /**
     * Update post
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param UpdatePostRequest $request The HTTP request object containing post data.
     * @param Post $post The post to be updated.
     * @return PostResource Returns the updated PostResource.
     * @throws UpdateResourceFailedException
     */
    #[ResponseFromApiResource(PostResource::class, Post::class, Response::HTTP_OK)]
    public function update(Post $post, UpdatePostRequest $request): PostResource
    {
        // Create an UpdatePostDTO object using the request data
        $updatePostDTO = UpdatePostDTOFactory::make($request, $post);

        // Update the post
        $post = $this->postService->update($updatePostDTO);

        // Return the updated post as a resource
        return new PostResource($post);
    }

    /**
     * Show post
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param  Post  $post  The post to be shown.
     * @return PostResource Returns the post as a resource.
     *
     */
    #[ResponseFromApiResource(PostResource::class, Post::class, Response::HTTP_OK)]
    public function show(Post $post, ShowPostRequest $request): PostResource
    {
        // Return the post as a resource
        return new PostResource($post);
    }

    /**
     * Delete post
     *
     * This endpoint allows users to delete a post.
     *
     * @authenticated Indicates that users must be authenticated to access this endpoint.
     *
     * @param Post $post The post entity to be deleted.
     * @param DeletePostRequest $request public function destroy(Post $post, DeletePostRequest $request): JsonResponse
     * The post entity to be deleted.
     * @return JsonResponse Returns a response with no content upon successful deletion.
     *
     * @response 204 Indicates that the response will be a 204 No Content status.
     *
     * @throws DeleteResourceFailedException
     */
    public function destroy(Post $post, DeletePostRequest $request): JsonResponse
    {
        // Delete the post
        $this->postService->delete($post);

        // Return a response with no content (HTTP 204 status)
        return $this->noContent();
    }
}
