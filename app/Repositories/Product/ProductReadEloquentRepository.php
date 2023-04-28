<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Enums\General\SystemParams;
use App\Enums\Product\ProductStatus;
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
        $lengthPerPage = SystemParams::LENGTH_PER_PAGE;

        $query = $this->model::query()
            ->select(columns:  ['id', 'name', 'slug', 'price', 'stock', 'status', 'created_at']);

        if ($this->isDefined(attribute: $arguments['status'] ?? null)) {
            $query = $query->where(column: 'status', operator: '=',value: $arguments['status']);
        }

        if ($this->isDefined(attribute: $queryParams['name'] ?? null)) {
            $query = $query->where(column: 'name', operator: 'like', value: '%' . $queryParams['name'] . '%');
        }

        if ($this->isDefined(attribute: $queryParams['price'] ?? null)) {
            $query = $query->where(column: 'price', operator: 'like', value: '%' . $queryParams['price'] . '%');
        }

        if ($this->isDefined(attribute: $queryParams['minimum_price'] ?? null)) {
            $query = $query->where(column: 'price', operator: '>=', value: $queryParams['minimum_price']);
        }

        if ($this->isDefined(attribute: $queryParams['maximum_price'] ?? null)) {
            $query = $query->where(column: 'price', operator: '<=', value: $queryParams['maximum_price']);
        }

        if ($this->isDefined(attribute: $queryParams['stock'] ?? null)) {
            $query = $query->where(column: 'stock', operator: 'like', value: '%' . $queryParams['stock'] . '%');
        }

        if ($this->isDefined(attribute: $queryParams['status'] ?? null)) {
            $query = $query->where(column: 'status', operator: '=', value: $queryParams['status']);
        }

        if ($this->isDefined(attribute: $queryParams['per_page'] ?? null) && $queryParams['per_page'] <= $lengthPerPage) {
            $lengthPerPage = $queryParams['per_page'];
        }

        return $query->orderBy(column: 'created_at', direction: 'DESC')
            ->orderBy(column: 'id', direction: 'DESC')
            ->paginate(perPage: $lengthPerPage)->through(fn ($product) => [
                'name' => $product->name,
                'description' => $product->description,
                'images' => $product->images,
                'price' => number_format(num: $product->price, decimal_separator: ',', thousands_separator: '.'),
                'stock' => number_format(num: $product->stock, decimal_separator: ',', thousands_separator: '.'),
                'status_key' => $product->status,
                'status_value' => trans($product->status),
                'created_at' => $product->created_at->format('d-m-Y'),
                'show_url' => route(name: 'buyer.products.show', parameters: ['slug' => $product->slug]),
                'edit_url' => route(name: 'admin.products.edit', parameters: ['product' => $product->id]),
                'delete_url' => route(name: 'admin.products.destroy', parameters: ['product' => $product->id]),
            ]);
    }

    public function find(string $key, mixed $value): ?Product
    {
        return $this->model->where($key, $value)->first();
    }

    public function allStatuses(): array
    {
        return collect(ProductStatus::asArray())->map(fn ($status) => [
            'key' => $status,
            'value' => trans($status),
        ])->toArray();
    }
}
