<?php

declare(strict_types=1);

namespace App\Services\Post;

use App\DTO\Post\CreatePostDTO;
use App\DTO\Post\ListPostDTO;
use App\DTO\Post\UpdatePostDTO;
use App\Enums\AuthApiSection;
use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\DeleteResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Models\Post;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class PostService
{
    public function getList(ListPostDTO $listPostDTO): Collection|LengthAwarePaginator|array
    {
        $query = Post::query()
            ->when($listPostDTO->getStatus(), fn ($q) => $q->where('status', $listPostDTO->getStatus()))
            ->when(
                $listPostDTO->getQ(),
                fn ($q) => $q
                    ->where('title', 'like', '%' . $listPostDTO->getQ() . '%')
            )
            ->where('faculty_id', auth(AuthApiSection::Admin->value)->user()->faculty_id)
            ->orderBy($listPostDTO->getOrderBy(), $listPostDTO->getOrder()->value);

        return $listPostDTO->getPage() ? $query->paginate($listPostDTO->getLimit()) : $query->get();
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function create(CreatePostDTO $createPostDTO): Post
    {
        try {
            return Post::create($createPostDTO->toArray());

        } catch (Exception $exception) {
            Log::error('Error creating post service: ', [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'line' => $exception->getLine(),
            ]);
            throw new CreateResourceFailedException();
        }
    }

    /**
     * @throws UpdateResourceFailedException
     */
    public function update(UpdatePostDTO $updatePostDTO): Post
    {
        try {
            return Post::where('id', $updatePostDTO->getId())->update($updatePostDTO->toArray());

        } catch (Exception $exception) {
            Log::error('Error updating post service: ', [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ]);
            throw new UpdateResourceFailedException();
        }
    }

    /**
     * @throws DeleteResourceFailedException
     */
    public function delete(Post|int $postId): bool
    {
        try {
            $post = $this->getPostById($postId);
            return $post->delete();
        } catch (Exception $exception) {
            Log::error('Error deleting post service: ', [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ]);
            throw new DeleteResourceFailedException();
        }
    }

    public function getPostById(Post|int $post): Post
    {
        return $post instanceof Post ? $post : Post::find($post);
    }
}
