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
        $lengthPerPage = SystemParams::LENGTH_PER_PAGE;

        $query = $this->model::query()
            ->select('id', 'name', 'slug', 'price', 'stock', 'status', 'created_at');

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

        if ($this->isDefined($queryParams['per_page'] ?? null) && $queryParams['per_page'] <= $lengthPerPage) {
            $lengthPerPage = $queryParams['per_page'];
        }

        return $query->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate($lengthPerPage)->through(fn ($product) => [
                'name' => $product->name,
                'description' => $product->description,
                'images' => $product->images,
                'price' => number_format($product->price),
                'stock' => number_format($product->stock),
                'status_key' => $product->status,
                'status_value' => trans($product->status),
                'created_at' => $product->created_at->format('d-m-Y'),
                'show_url' => route('buyer.products.show', ['slug' => $product->slug]),
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

            $newImagesPaths = $this->fileService->uploadMultipleFiles(files: $data['images'], relativePath: $this::PRODUCTS_GALLERY_PATH);

            $product = $this->model::create([
                'name' => $this->normalizeStringUsingUcwords($data['name']),
                'slug' => $slug,
                'price' => $data['price'],
                'stock' => $data['stock'],
                'status' => $data['stock'] > 0 ? $data['status'] : ProductStatus::UNAVAILABLE,
                'description' => $this->normalizeStringUsingUcfirst($data['description']),
            ]);

            foreach ($newImagesPaths as $imagePath) {
                $product->images()->create([
                    'path' => $imagePath
                ]);
            }

            return $product;
        } catch (\Throwable $throwable) {
            return null;
        }
    }

    public function update(array $data, int $id): bool
    {
        try {
            $product = $this->find(id: $id);

            $currentImagesPaths = $product->images()->pluck(column: 'path')->toArray();

            $newImagesPaths = [];

            $preloadedImagesPaths = array_map(function ($image) {
                return $image['path'];
            }, array: $data['preloaded_images'] ?? []);

            $imagesToRemove = array_diff($currentImagesPaths, $preloadedImagesPaths);

            if (isset($data['images']) && count($data['images']) > 0) {
                $newImagesPaths = $this->fileService->uploadMultipleFiles(files: $data['images'], relativePath: $this::PRODUCTS_GALLERY_PATH);
            }

            if (count($imagesToRemove) > 0) {
                $this->fileService->removeMultipleFiles(fullPaths: $imagesToRemove, fromThePrefix: '/images');

                $product->images()
                    ->whereIn(column: 'path', values: $imagesToRemove)
                    ->delete();
            }

            foreach ($newImagesPaths as $imagePath) {
                $product->images()->create([
                    'path' => $imagePath
                ]);
            }

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

    public function findByParam(string $key, mixed $value): ?Product
    {
        return $this->model->where($key, $value)->first();
    }
}
