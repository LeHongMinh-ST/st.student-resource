<?php

declare(strict_types=1);

namespace App\DTO\Post;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;
use App\Enums\PostStatus;

class ListPostDTO extends BaseListDTO implements BaseDTO
{
    private ?string $q;
    private ?PostStatus $status;

    public function toArray(): array
    {
        return [];
    }

    public function getQ(): ?string
    {
        return $this->q;
    }

    public function setQ(?string $q): void
    {
        $this->q = $q;
    }

    public function getStatus(): ?PostStatus
    {
        return $this->status;
    }

    public function setStatus(?PostStatus $status): void
    {
        $this->status = $status;
    }
}
