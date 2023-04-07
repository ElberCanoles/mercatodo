<?php

declare(strict_types=1);

namespace App\Repositories;


interface RepositoryInterface
{
    public function all(array $queryParams = [], ...$arguments);

    public function store(array $data);

    public function update(array $data, int $id);

    public function delete(int $id);

    public function find(int $id);
}
