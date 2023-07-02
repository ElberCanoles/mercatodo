<?php

namespace Tests\Unit\Repositories\Product\WriteEloquentRepository;

use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Repositories\ProductWriteEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Name',
            'price' => '10',
            'stock' => '10',
            'status' => ProductStatus::UNAVAILABLE,
            'description' => 'Description'
        ]);
        $repository = resolve(name: ProductWriteEloquentRepository::class);

        $repository->update(data: [
            'name' => 'New name',
            'price' => '100',
            'stock' => '100',
            'status' => ProductStatus::AVAILABLE,
            'description' => 'New description'
        ], id: $product->id);

        $this->assertDatabaseCount(table: 'products', count: 1);

        $this->assertDatabaseHas(table: 'products', data: [
            'name' => 'New name',
            'price' => '100',
            'stock' => '100',
            'status' => ProductStatus::AVAILABLE,
            'description' => 'New description'
        ]);
    }

    public function test_update_product_returns_false_on_exception(): void
    {
        $repository = resolve(name: ProductWriteEloquentRepository::class);

        $response = $repository->update(data: [
            'name' => 'New name',
            'price' => '100',
            'stock' => '100',
            'status' => ProductStatus::AVAILABLE,
            'description' => 'New description'
        ], id: 0);

        $this->assertFalse($response);
    }
}
