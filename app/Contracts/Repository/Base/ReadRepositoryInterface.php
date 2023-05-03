<?php

declare(strict_types=1);

namespace App\Contracts\Repository\Base;

use Illuminate\Pagination\LengthAwarePaginator;

interface ReadRepositoryInterface
{
    public function all(array $queryParams = [], ...$arguments): LengthAwarePaginator;

    public function find(string $key, mixed $value): ?object;
}
