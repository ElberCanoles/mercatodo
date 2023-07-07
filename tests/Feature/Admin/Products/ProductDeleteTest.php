<?php

namespace Tests\Feature\Admin\Products;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\Permission;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $this->admin->givePermissionTo(permission: Permission::create(['name' => Permissions::PRODUCTS_DELETE]));
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
}
