<?php

namespace App\Pagination;

use App\Repositories\Repository;

class Paginator
{
    private int $lastPageNumber;
    private int $page;

    public function __construct(
        private Repository $repository,
        private int $limit,
        int $page,
        private int $size = 3,
        private array $filters = [],
    ) {
        $this->lastPageNumber = ceil($this->repository->count($filters) / $this->limit) - 1;

        if ($page > $this->lastPageNumber) {
            $this->page = $this->lastPageNumber;
        } elseif ($page < 0) {
            $this->page = 0;
        } else {
            $this->page = $page;
        }
    }

    public function getItems(): array
    {
        return $this->repository->getPaginated($this->limit, $this->page * $this->limit, $this->filters);
    }

    public function has(int $index): bool
    {
        $page = $this->page + $index;

        return $page >= 0 && $page <= $this->lastPageNumber;
    }

    public function getPageNumber(int $index): ?int
    {
        return $this->has($index) ? $this->page + $index: null;
    }

    public function isCurrentPageNumber(int $page): bool
    {
        return $this->page === $page;
    }

    public function reachedLeft(): bool
    {
        return ($this->page - $this->size) < 0;
    }

    public function reachedRight(): bool
    {
        return ($this->page + $this->size) > $this->lastPageNumber;
    }

    public function getFirstPageNumber(): int
    {
        return 0;
    }

    public function getLastPageNumber(): int
    {
        return $this->lastPageNumber;
    }

    public function getDisplayPages(): array
    {
        if ($this->lastPageNumber === 0) {
            return [];
        }

        $start = -$this->size + 1;
        $end = $this->size - 1;

        $pages = [];

        for ($i = $start; $i <= $end; $i++) {
            if ($this->has($i)) {
                $pages[] = $this->getPageNumber($i);
            }
        }

        return $pages;
    }

    public function hasPreviousPage(): bool
    {
        return $this->has(-1);
    }

    public function hasNextPage(): bool
    {
        return $this->has(1);
    }

    public function getPreviousPageNumber(): ?int
    {
        return $this->getPageNumber(-1);
    }

    public function getNextPageNumber(): ?int
    {
        return $this->getPageNumber(1);
    }

    public function hasLeftDots(): bool
    {
        return $this->page - $this->size > 0;
    }

    public function hasRightDots(): bool
    {
        return $this->page + $this->size < $this->lastPageNumber;
    }
}