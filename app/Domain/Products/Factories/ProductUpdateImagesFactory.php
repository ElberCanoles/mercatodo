<?php

declare(strict_types=1);

namespace App\Domain\Products\Factories;

use App\Domain\Products\Models\Product;
use App\Domain\Shared\Services\FileService;
use Exception;

class ProductUpdateImagesFactory
{
    public const PRODUCTS_GALLERY_PATH = 'images/products';

    public function __construct(private readonly FileService $fileService)
    {
    }

    public function create(Product $product, array $preloadedImages = null, array $newImages = null): void
    {
        try {
            $currentImagesPaths = $product->images()->get()->pluck(value: 'path')->toArray();

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
        } catch (Exception $exception) {
            logger()->error(message: 'error updating product images', context: [
                'module' => 'ProductUpdateImagesFactory.create',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ]);
        }
    }
}
