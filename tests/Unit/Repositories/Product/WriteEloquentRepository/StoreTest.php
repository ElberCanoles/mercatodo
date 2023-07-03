<?php

namespace Tests\Unit\Repositories\Product\WriteEloquentRepository;

use App\Domain\Products\Models\Product;
use App\Domain\Products\Repositories\ProductWriteEloquentRepository;
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

        $repository = resolve(name: ProductWriteEloquentRepository::class);

        $repository->store(data: [
            'name' => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
            'status' => $product->status,
            'description' => $product->description
        ]);

        $this->assertDatabaseCount(table: 'products', count: 1);

        $this->assertDatabaseHas(table: 'products', data: [
            'name' => 'Name',
        ]);
    }

    public function test_store_product_returns_null_on_exception(): void
    {
        $repository = resolve(name: ProductWriteEloquentRepository::class);

        $result = $repository->store(data: []);

        $this->assertNull($result);
    }
}
