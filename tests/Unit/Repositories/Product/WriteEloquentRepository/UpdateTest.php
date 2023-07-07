<?php

namespace Tests\Unit\Repositories\Product\WriteEloquentRepository;

use App\Domain\Products\Actions\UpdateProductAction;
use App\Domain\Products\DataTransferObjects\UpdateProductData;
use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Models\Product;
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
        $action = resolve(name: UpdateProductAction::class);

        $action->execute(UpdateProductData::fromArray([
            'name' => 'New name',
            'price' => '100',
            'stock' => '100',
            'status' => ProductStatus::AVAILABLE,
            'description' => 'New description'
        ]), $product);

        $this->assertDatabaseCount(table: 'products', count: 1);

        $this->assertDatabaseHas(table: 'products', data: [
            'name' => 'New name',
            'price' => '100',
            'stock' => '100',
            'status' => ProductStatus::AVAILABLE,
            'description' => 'New description'
        ]);
    }
}
