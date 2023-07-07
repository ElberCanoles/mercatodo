<?php

namespace Tests\Unit\Repositories\Product\WriteEloquentRepository;

use App\Domain\Products\Actions\StoreProductAction;
use App\Domain\Products\DataTransferObjects\StoreProductData;
use App\Domain\Products\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_product(): void
    {
        $product = Product::factory()->make([
            'name' => 'Name',
        ]);

        $action = resolve(name: StoreProductAction::class);

        $action->execute(StoreProductData::fromArray([
            'name' => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
            'status' => $product->status,
            'description' => $product->description
        ]));

        $this->assertDatabaseCount(table: 'products', count: 1);

        $this->assertDatabaseHas(table: 'products', data: [
            'name' => 'Name',
        ]);
    }
}
