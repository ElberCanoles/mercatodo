<?php

namespace Tests\Feature\Admin\Products;

use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCreateTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole(role: Roles::ADMINISTRATOR);
    }

    public function test_admin_can_access_to_products_create_screen(): void
    {
        $readRepository = resolve(name: ProductReadRepositoryInterface::class);

        $response = $this
            ->actingAs($this->admin)
            ->get(route(name: 'admin.products.create'));

        $response->assertOk()
            ->assertViewIs(value: 'admin.products.crud.create')
            ->assertViewHas('statuses', $readRepository->allStatuses());
    }
}
