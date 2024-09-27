<?php

declare(strict_types=1);

namespace App\DTO\Post;

use App\Enums\PostStatus;

class CreatePostDTO
{
    private string $title;
    private string $content;
    private int $userId;
    private PostStatus $status;
    private int $facultyId;

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

    public function getFacultyId(): int
    {
        return $this->facultyId;
    }

    public function setFacultyId(int $facultyId): void
    {
        $this->facultyId = $facultyId;
    }



    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'user_id' => $this->userId,
            'status' => $this->status,
            'faculty_id' => $this->facultyId,
        ];
    }
}
