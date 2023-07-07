<?php

namespace Tests\Feature\Admin\Products;

use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
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
        $this->seed(class: RoleSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole(role: Roles::ADMINISTRATOR);
        $this->product = Product::factory()->create();
    }

    public function test_admin_can_delete_products(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->delete(route(name: 'admin.products.destroy', parameters: ['product' => $this->product->id]));

        $response->assertOk();

        $this->product->refresh();

        $this->assertNotNull($this->product->deleted_at);
    }

    public function test_admin_can_not_delete_products_when_internal_error(): void
    {
        $this->mock(abstract: ProductWriteRepositoryInterface::class, mock: function ($mock) {
            $mock->shouldReceive('delete')->andReturn(false);
        });

        $response = $this
            ->actingAs($this->admin)
            ->delete(route(name: 'admin.products.destroy', parameters: ['product' => $this->product->id]));

        $response->assertStatus(status: 500);

        Mockery::close();
    }
}
