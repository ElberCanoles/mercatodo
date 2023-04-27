<?php

declare(strict_types=1);

namespace App\Contracts\Repository\Base;

interface ReadRepositoryInterface
{
    public function all(array $queryParams = [], ...$arguments);

    public function find(string $key, mixed $value);
}
