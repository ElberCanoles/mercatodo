<?php declare(strict_types=1);

namespace App\Domain\Products\Actions;

use App\Domain\Products\DataTransferObjects\UpdateProductData;
use App\Domain\Products\Factories\ProductUpdateImagesFactory;
use App\Domain\Products\Models\Product;
use App\Domain\Shared\Services\SlugeableService;

class UpdateProductAction
{
    public function __construct(
        private readonly SlugeableService           $slugService,
        private readonly ProductUpdateImagesFactory $updateImagesFactory)
    {
    }

    public function execute(UpdateProductData $data, Product $product): void
    {
        $product->fill([
            'name' => $data->name,
            'price' => $data->price,
            'stock' => $data->stock,
            'status' => $data->status,
            'description' => $data->description,
        ]);

        if ($product->isDirty(attributes: 'name')) {
            $slug = $this->slugService->getUniqueSlugByEloquentModel(
                input: $data->name,
                model: new Product(),
                columName: 'slug'
            );

            $product->slug = $slug;
        }

        $this->updateImagesFactory->create(
            product: $product,
            preloadedImages: $data->preloaded_images ?? null,
            newImages: $data->images ?? null
        );

        $product->save();
    }

}
