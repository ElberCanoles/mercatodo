<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Product;

use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\Permission;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditProductControllerTest extends TestCase
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
        $this->admin->givePermissionTo(permission: Permission::create(['name' => Permissions::PRODUCTS_UPDATE]));
        $this->product = Product::factory()->create();
    }

    public function test_admin_can_access_to_products_edit_screen(): void
    {
        $statuses = collect(ProductStatus::asArray())->map(fn ($status) => [
            'key' => $status,
            'value' => trans($status),
        ])->toArray();

        $response = $this
            ->actingAs($this->admin)
            ->get(route(name: 'admin.products.edit', parameters: ['product' => $this->product->id]));

        $response->assertOk()
            ->assertViewIs(value: 'admin.products.crud.edit')
            ->assertViewHas('product', $this->product)
            ->assertViewHas('statuses', $statuses);
    }
}
