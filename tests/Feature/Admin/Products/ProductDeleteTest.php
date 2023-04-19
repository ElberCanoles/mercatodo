<?php

namespace Tests\Feature\Admin\Products;

use App\Enums\User\RoleType;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Product\ProductRepositoryInterface;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ProductDeleteTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Product $product;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->admin = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);
        $this->product = Product::factory()->create();
    }

    public function test_admin_can_delete_products(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->delete(route('admin.products.destroy', ['product' => $this->product->id]));

        $response->assertOk();

        $this->product->refresh();

        $this->assertNotNull($this->product->deleted_at);
    }

    public function test_admin_can_not_delete_products_when_internal_error(): void
    {
        $this->mock(ProductRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('delete')->andReturn(null);
        });

        $response = $this
            ->actingAs($this->admin)
            ->delete(route('admin.products.destroy', ['product' => $this->product->id]));

        $response->assertStatus(500);

        Mockery::close();
    }
}
