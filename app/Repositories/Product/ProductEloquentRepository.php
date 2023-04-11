<?php

namespace App\Repositories\Product;

use App\Enums\General\SystemParams;
use App\Enums\Product\ProductStatus;
use App\Models\Product;
use App\Repositories\Repository;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProductEloquentRepository extends Repository implements ProductRepositoryInterface
{

    public function __construct(private Product $model)
    {
    }

    public function all(array $queryParams = [], ...$arguments): LengthAwarePaginator
    {
        $query = $this->model::query()
            ->select('id', 'name', 'price', 'stock', 'status', 'created_at');

        if ($this->isDefined($arguments['withoutGlobalScope'] ?? null)) {
            $query = $query->withoutGlobalScope($arguments['withoutGlobalScope']);
        }

        if ($this->isDefined($queryParams['name'] ?? null)) {
            $query = $query->where('name', 'like', '%' . $queryParams['name'] . '%');
        }

        if ($this->isDefined($queryParams['price'] ?? null)) {
            $query = $query->where('price', 'like', '%' . $queryParams['price'] . '%');
        }

        if ($this->isDefined($queryParams['stock'] ?? null)) {
            $query = $query->where('stock', 'like', '%' . $queryParams['stock'] . '%');
        }

        if ($this->isDefined($queryParams['status'] ?? null)) {
            $query = $query->where('status', $queryParams['status']);
        }

        return $query->orderBy('created_at', 'DESC')
            ->paginate(SystemParams::LENGTH_PER_PAGE)->through(fn ($product) => [
                'name' => $product->name,
                'price' => number_format($product->price),
                'stock' => number_format($product->stock),
                'status_key' => $product->status,
                'status_value' => trans($product->status),
                'created_at' => $product->created_at->format('d-m-Y'),
                'edit_url' => route('admin.products.edit', ['product' => $product->id]),
            ]);
    }

    public function store(array $data)
    {
        // TODO: Implement store() method.
    }

    public function update(array $data, int $id)
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    public function find(int $id)
    {
        // TODO: Implement find() method.
    }

    /**
     * Get all product statuses
     *
     * @return array
     */
    public function allStatuses(): array
    {
        return collect(ProductStatus::asArray())->map(fn($status) => [
            'key' => $status,
            'value' => trans($status)
        ])->toArray();
    }
}
