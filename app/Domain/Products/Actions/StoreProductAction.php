<?php declare(strict_types=1);

namespace App\Domain\Products\Actions;

use App\Domain\Products\DataTransferObjects\StoreProductData;
use App\Domain\Products\Factories\ProductStoreImagesFactory;
use App\Domain\Products\Models\Product;
use App\Domain\Shared\Services\SlugeableService;

class StoreProductAction
{
    public function __construct(
        private readonly SlugeableService          $slugService,
        private readonly ProductStoreImagesFactory $storeImagesFactory)
    {
    }

    public function execute(StoreProductData $data): void
    {
        $product = Product::create([
            'name' => $data->name,
            'slug' => $this->slugService->getUniqueSlugByEloquentModel(
                input: $data->name,
                model: new Product(),
                columName: 'slug'
            ),
            'price' => $data->price,
            'stock' => $data->stock,
            'status' => $data->status,
            'description' => $data->description,
        ]);

        if (isset($data->images)) $this->storeImagesFactory->create($product, $data->images);
    }
}
