<?php

namespace Tests\Unit\Repositories\Product\ReadEloquentRepository;

use App\Models\Product;
use App\Repositories\Product\ProductReadEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FindTest extends TestCase
{
    use RefreshDatabase;

    public function test_find_return_product_object_when_exists(): void
    {
        $product = Product::factory()->create();

        $repository = new ProductReadEloquentRepository(new Product());
        $result = $repository->find('id', $product->id);

        $this->assertEquals(expected: $product->id, actual: $result->id);
        $this->assertEquals(expected: $product->name, actual: $result->name);
        $this->assertEquals(expected: $product->description, actual: $result->description);
        $this->assertEquals(expected: $product->price, actual: $result->price);
        $this->assertEquals(expected: $product->status, actual: $result->status);
    }

    public function test_find_return_null_when_not_exists(): void
    {
        $repository = new ProductReadEloquentRepository(new Product());
        $result = $repository->find('id', '0');

        $this->assertNull($result);
    }
}
