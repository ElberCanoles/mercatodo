<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Enums\Product\ProductStatus;
use App\Factories\Product\ProductAllFactory;
use App\Models\Product;
use App\Repositories\Repository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductReadEloquentRepository extends Repository implements ProductReadRepositoryInterface
{
    public function __construct(private readonly Product $model)
    {
    }

    public function all(array $queryParams = [], ...$arguments): LengthAwarePaginator
    {
        $factory = new ProductAllFactory($this->model, $queryParams, ...$arguments);
        return $factory->make();
    }

    public function allStatuses(): array
    {
        return collect(ProductStatus::asArray())->map(fn ($status) => [
            'key' => $status,
            'value' => trans($status),
        ])->toArray();
    }

    public function find(string $key, mixed $value): ?Product
    {
        return $this->model->where($key, $value)->first();
    }

    public function findAvailable(string $key, mixed $value): ?Product
    {
        return $this->model->where($key, $value)
            ->where('status', ProductStatus::AVAILABLE)
            ->first();
    }
}
