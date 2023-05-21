<?php

declare(strict_types=1);

namespace App\Factories\Product;

use App\Models\Product;
use App\Services\Utilities\FileService;

class ProductUpdateImagesFactory
{
    public const PRODUCTS_GALLERY_PATH = 'images/products';

    public function __construct(private readonly FileService $fileService)
    {
    }

    public function make(Product $product, array $preloadedImages = null, array $newImages = null): void
    {
        try {
            $currentImagesPaths = $product->images()->pluck(column: 'path')->toArray();

            $newImagesPaths = [];

            $preloadedImagesPaths = array_map(function ($image) {
                return $image['path'];
            }, array: $preloadedImages ?? []);

            $imagesToRemove = array_diff($currentImagesPaths, $preloadedImagesPaths);

            if (isset($newImages) && count($newImages) > 0) {
                $newImagesPaths = $this->fileService->uploadMultipleFiles(
                    files: $newImages,
                    relativePath: $this::PRODUCTS_GALLERY_PATH
                );
            }

            if (count($imagesToRemove) > 0) {
                $this->fileService->removeMultipleFiles(
                    fullPaths: $imagesToRemove,
                    fromThePrefix: '/images'
                );

                $product->images()
                    ->whereIn(column: 'path', values: $imagesToRemove)
                    ->delete();
            }

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
