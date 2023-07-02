<?php

declare(strict_types=1);

namespace App\Domain\Products\Repositories;

use App\Contracts\Repository\Base\Repository;
use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Domain\Products\Factories\ProductStoreImagesFactory;
use App\Domain\Products\Factories\ProductUpdateImagesFactory;
use App\Domain\Products\Models\Product;
use App\Services\Utilities\SlugeableService;

final class ProductWriteEloquentRepository extends Repository implements ProductWriteRepositoryInterface
{
    public function __construct(
        private readonly Product                    $model,
        private readonly SlugeableService           $slugeableService,
        private readonly ProductStoreImagesFactory  $storeImagesFactory,
        private readonly ProductUpdateImagesFactory $updateImagesFactory
    )
    {
    }

    public function store(array $data): ?Product
    {
        try {
            $product = $this->model::create([
                'name' => $this->normalizeStringUsingUcfirst($data['name']),
                'slug' => $this->slugeableService->getUniqueSlugByEloquentModel(
                    input: $data['name'],
                    model: $this->model,
                    columName: 'slug'
                ),
                'price' => $data['price'],
                'stock' => $data['stock'],
                'status' => $data['status'],
                'description' => $this->normalizeStringUsingUcfirst($data['description']),
            ]);

            if (isset($data['images'])) $this->storeImagesFactory->create($product, $data['images']);

            return $product;
        } catch (\Throwable $throwable) {
            report(exception: $throwable);
            return null;
        }
    }

    public function update(array $data, int $id): bool
    {
        try {
            $product = $this->model::find($id);

            $product->fill([
                'name' => $this->normalizeStringUsingUcfirst($data['name']),
                'price' => $data['price'],
                'stock' => $data['stock'],
                'status' => $data['status'],
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

            $this->updateImagesFactory->create(
                product: $product,
                preloadedImages: $data['preloaded_images'] ?? null,
                newImages: $data['images'] ?? null
            );

            return $product->save();
        } catch (\Throwable $throwable) {
            report(exception: $throwable);
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $product = $this->model::find($id);

            $product->delete();

            return true;
        } catch (\Throwable $throwable) {
            report(exception: $throwable);
            return false;
        }
    }
}
