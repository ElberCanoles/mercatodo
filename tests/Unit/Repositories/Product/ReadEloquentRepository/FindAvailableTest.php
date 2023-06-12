<?php

namespace Tests\Unit\Repositories\Product\ReadEloquentRepository;

use App\Enums\Product\ProductStatus;
use App\Models\Product;
use App\Repositories\Product\ProductReadEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FindAvailableTest extends TestCase
{
    use RefreshDatabase;

    public function test_find_available_return_product_object_when_status_is_available(): void
    {
        $product = Product::factory()->create(['status' => ProductStatus::AVAILABLE]);

        $repository = new ProductReadEloquentRepository(new Product());
        $result = $repository->findAvailable(key: 'slug', value: $product->slug);

        $this->assertEquals(expected: $product->id, actual: $result->id);
        $this->assertEquals(expected: $product->name, actual: $result->name);
        $this->assertEquals(expected: $product->description, actual: $result->description);
        $this->assertEquals(expected: $product->price, actual: $result->price);
        $this->assertEquals(expected: $product->status, actual: $result->status);
    }

    public function test_find_available_return_null_when_status_is_not_available(): void
    {
        $product = Product::factory()->create(['status' => ProductStatus::UNAVAILABLE]);

        $repository = new ProductReadEloquentRepository(new Product());
        $result = $repository->findAvailable(key: 'slug', value: $product->slug);

        $this->assertDatabaseCount(table: 'products', count: 1);
        $this->assertDatabaseHas(table: 'products', data: [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug
        ]);
        $this->assertNull($result);
    }
}
