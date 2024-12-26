<?php

declare(strict_types=1);

namespace App\DTO\Post;

use App\Enums\PostStatus;

class UpdatePostDTO
{
    private int $id;
    private string $title;
    private string $content;
    private int $userId;
    private PostStatus $status;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getStatus(): PostStatus
    {
        return $this->status;
    }

    public function setStatus(PostStatus $status): void
    {
        $this->status = $status;
    }

    public function toArray(): array
    {
        $post = [
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status
        ];

        return array_filter($post, fn ($value) => null !== $value);
    }
}
