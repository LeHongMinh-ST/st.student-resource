<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enums\SortOrder;
use App\Supports\Constants;

abstract class BaseListDTO
{
    protected ?string $search;

    protected int $limit;

    protected SortOrder $order;

    protected string $orderBy;

    protected ?int $page;

    public function __construct()
    {
        $this->order = SortOrder::DESC;
        $this->limit = Constants::PAGE_LIMIT;
        $this->orderBy = 'created_at';
        $this->page = null;
        $this->search = '';
    }

    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function getOrder(): SortOrder
    {
        return $this->order;
    }

    public function setOrder(SortOrder $order): void
    {
        $this->order = $order;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(?int $page): void
    {
        $this->page = $page;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }


}
