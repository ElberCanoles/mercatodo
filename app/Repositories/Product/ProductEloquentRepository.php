<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Enums\General\SystemParams;
use App\Enums\Product\ProductStatus;
use App\Models\Product;
use App\Repositories\Repository;
use App\Services\Utilities\FileService;
use App\Services\Utilities\SlugeableService;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProductEloquentRepository extends Repository implements ProductRepositoryInterface
{
    public const PRODUCTS_GALLERY_PATH = 'images/products';

    public function __construct(
        private readonly Product          $model,
        private readonly SlugeableService $slugeableService,
        private readonly FileService      $fileService
    ) {
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
                'delete_url' => route('admin.products.destroy', ['product' => $product->id]),
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

            $filePaths = $this->fileService->uploadMultipleFiles(files: $data['photos'], relativePath: $this::PRODUCTS_GALLERY_PATH);

            $product = $this->model::create([
                'name' => $this->normalizeStringUsingUcwords($data['name']),
                'slug' => $slug,
                'price' => $data['price'],
                'stock' => $data['stock'],
                'status' => $data['stock'] > 0 ? $data['status'] : ProductStatus::UNAVAILABLE,
                'description' => $this->normalizeStringUsingUcfirst($data['description']),
            ]);

            foreach ($filePaths as $filePath) {
                $product->images()->create([
                    'path' => $filePath
                ]);
            }

            return $product;
        } catch (\Throwable $throwable) {
            return null;
        }
    }

    public function update(array $data, int $id): bool
    {
        $product = $this->find(id: $id);

        try {
            $product->fill([
                'name' => $this->normalizeStringUsingUcwords($data['name']),
                'price' => $data['price'],
                'stock' => $data['stock'],
                'status' => $data['stock'] > 0 ? $data['status'] : ProductStatus::UNAVAILABLE,
                'description' => $this->normalizeStringUsingUcfirst($data['description']),
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

    /**
     * Delete one product on database
     */
    public function delete(int $id): bool
    {
        try {
            $this->model->destroy($id);

            return true;
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * Get one product record by id
     */
    public function find(int $id): ?Product
    {
        return $this->model->find($id);
    }

    /**
     * Get all product statuses
     */
    public function allStatuses(): array
    {
        return collect(ProductStatus::asArray())->map(fn ($status) => [
            'key' => $status,
            'value' => trans($status),
        ])->toArray();
    }
}
