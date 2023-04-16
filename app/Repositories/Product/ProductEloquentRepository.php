<?php

namespace App\Repositories\Product;

use App\Enums\General\SystemParams;
use App\Enums\Product\ProductStatus;
use App\Models\Product;
use App\Repositories\Repository;
use App\Services\Utilities\SlugeableService;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProductEloquentRepository extends Repository implements ProductRepositoryInterface
{

    public function __construct(private Product $model, private SlugeableService $slugeableService)
    {
    }

    public function all(array $queryParams = [], ...$arguments): LengthAwarePaginator
    {
        $query = $this->model::query()
            ->select('id', 'name', 'price', 'stock', 'status', 'created_at');

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
                'delete_url' => route('admin.products.destroy', ['product' => $product->id])
            ]);
    }

    public function store(array $data): ?Product
    {
        try {

            $slug = $this->slugeableService->getUniqueSlugByEloquentModel(
                input: $data['name'],
                model: $this->model,
                columName: 'slug'
            );

            return $this->model::create([
                'name' => $this->normalizeStringUsingUcwords($data['name']),
                'slug' => $slug,
                'price' => $this->normalizeNumberUsingAbs($data['price']),
                'stock' => $this->normalizeNumberUsingAbs($data['stock']),
                'status' => $data['stock'] > 0 ? $data['status'] : ProductStatus::UNAVAILABLE,
                'description' => $this->normalizeStringUsingUcfirst($data['description'])
            ]);
        } catch (\Throwable $throwable) {
            dd($throwable);
            return null;
        }
    }

    public function update(array $data, int $id)
    {
        $product = $this->find(id: $id);

        try {

            $product->fill([
                'name' => $this->normalizeStringUsingUcwords($data['name']),
                'price' => $this->normalizeNumberUsingAbs($data['price']),
                'stock' => $this->normalizeNumberUsingAbs($data['stock']),
                'status' => $data['stock'] > 0 ? $data['status'] : ProductStatus::UNAVAILABLE,
                'description' => $this->normalizeStringUsingUcfirst($data['description'])
            ]);

            if ($product->isDirty('name')) {

                $slug = $this->slugeableService->getUniqueSlugByEloquentModel(
                    input: $data['name'],
                    model: $this->model,
                    columName: 'slug'
                );

                $product->slug = $slug;
            }

            return $product->save();
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    public function find(int $id): ?Product
    {
        return $this->model->find($id);
    }

    /**
     * Get all product statuses
     *
     * @return array
     */
    public function allStatuses(): array
    {
        return collect(ProductStatus::asArray())->map(fn ($status) => [
            'key' => $status,
            'value' => trans($status)
        ])->toArray();
    }
}
