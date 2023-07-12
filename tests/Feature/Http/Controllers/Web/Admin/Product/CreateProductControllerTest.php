<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Product;

use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\Permission;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole(role: Roles::ADMINISTRATOR);
        $this->admin->givePermissionTo(permission: Permission::create(['name' => Permissions::PRODUCTS_CREATE]));
    }

    public function test_admin_can_access_to_products_create_screen(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route(name: 'admin.products.create'));

        $statuses = collect(ProductStatus::asArray())->map(fn ($status) => [
            'key' => $status,
            'value' => trans($status),
        ])->toArray();

        $response->assertOk()
            ->assertViewIs(value: 'admin.products.crud.create')
            ->assertViewHas('statuses', $statuses);
    }
}
