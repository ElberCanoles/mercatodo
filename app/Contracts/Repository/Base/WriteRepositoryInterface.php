<?php

declare(strict_types=1);

namespace App\Contracts\Repository\Base;

interface WriteRepositoryInterface
{
    public function store(array $data): ?object;

    public function update(array $data, int $id): bool;

    public function delete(int $id): bool;
}
