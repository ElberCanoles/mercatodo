<?php

namespace Tests\Unit\Repositories\Product\WriteEloquentRepository;

use App\Domain\Products\Actions\DestroyProductAction;
use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Models\Product;
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

        (new DestroyProductAction())->execute($product);

        $this->assertSoftDeleted(table: 'products', data: $data);
    }
}
