<?php

declare(strict_types=1);

namespace App\Domain\Products\Factories;

use App\Domain\Products\Models\Product;
use App\Domain\Shared\Services\FileService;
use Throwable;

class ProductStoreImagesFactory
{
    public const PRODUCTS_GALLERY_PATH = 'images/products';

    public function __construct(private readonly FileService $fileService)
    {
    }

    public function create(Product $product, array $images): void
    {
        try {
            $newImagesPaths = $this->fileService->uploadMultipleFiles(
                files: $images,
                relativePath: $this::PRODUCTS_GALLERY_PATH
            );

            foreach ($newImagesPaths as $imagePath) {
                $product->images()->create([
                    'path' => $imagePath
                ]);
            }
        } catch (Throwable $throwable) {
            report(exception: $throwable);
        }
    }
}
