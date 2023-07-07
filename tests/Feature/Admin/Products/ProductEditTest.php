<?php

namespace Tests\Feature\Admin\Products;

use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductEditTest extends TestCase
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

    public function test_admin_can_access_to_products_edit_screen(): void
    {
        $readRepository = resolve(name: ProductReadRepositoryInterface::class);

        $response = $this
            ->actingAs($this->admin)
            ->get(route(name: 'admin.products.edit', parameters: ['product' => $this->product->id]));

        $response->assertOk()
            ->assertViewIs(value: 'admin.products.crud.edit')
            ->assertViewHas('product', $this->product)
            ->assertViewHas('statuses', $readRepository->allStatuses());
    }
}
