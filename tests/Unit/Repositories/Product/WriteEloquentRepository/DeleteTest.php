<?php

namespace Tests\Unit\Repositories\Product\WriteEloquentRepository;

use App\Enums\Product\ProductStatus;
use App\Models\Product;
use App\Repositories\Product\ProductWriteEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_product(): void
    {
        $data = [
            'name' => 'Name',
            'slug' => Str::slug(title: 'Name'),
            'price' => '10',
            'stock' => '10',
            'status' => ProductStatus::AVAILABLE,
            'description' => 'Description'
        ];

        $product = Product::create($data);

        $repository = resolve(name: ProductWriteEloquentRepository::class);

        $response = $repository->delete($product->id);

        $this->assertTrue($response);

        $this->assertSoftDeleted(table: 'products', data: $data);
    }

    public function test_delete_product_returns_false_on_exception(): void
    {
        $repository = resolve(name: ProductWriteEloquentRepository::class);

        $response = $repository->delete(0);

        $this->assertFalse($response);
    }
}
