<?php

declare(strict_types=1);

namespace App\Patterns\Factory\Product;

use App\Models\Product;
use App\Services\Utilities\FileService;

class ProductStoreImagesFactory
{
    public const PRODUCTS_GALLERY_PATH = 'images/products';

    public function __construct(private readonly FileService $fileService)
    {
    }

    public function make(Product $product, array $images): void
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
        } catch (\Throwable $throwable) {
            report(exception: $throwable);
        }
    }
}
