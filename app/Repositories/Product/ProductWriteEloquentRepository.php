<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Enums\Product\ProductStatus;
use App\Models\Product;
use App\Repositories\Repository;
use App\Services\Utilities\FileService;
use App\Services\Utilities\SlugeableService;

final class ProductWriteEloquentRepository extends Repository implements ProductWriteRepositoryInterface
{
    public const PRODUCTS_GALLERY_PATH = 'images/products';

    public function __construct(
        private readonly Product          $model,
        private readonly SlugeableService $slugeableService,
        private readonly FileService      $fileService
    ) {
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
            $product = $this->model::find($id);

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

    public function delete(int $id): bool
    {
        try {
            $this->model->destroy($id);

            return true;
        } catch (\Throwable $throwable) {
            return false;
        }
    }
}
