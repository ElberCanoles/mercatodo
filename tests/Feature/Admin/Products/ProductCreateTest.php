<?php

namespace Tests\Feature\Admin\Products;

use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Enums\User\RoleType;
use App\Models\User;
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
        $this->seed(RoleSeeder::class);
        $this->admin = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);
    }

    public function test_admin_can_access_to_products_create_screen(): void
    {
        $readRepository = resolve(name: ProductReadRepositoryInterface::class);

        $response = $this
            ->actingAs($this->admin)
            ->get(route('admin.products.create'));

        $response->assertOk()
            ->assertViewIs('admin.products.crud.create')
            ->assertViewHas('statuses', $readRepository->allStatuses());
    }
}
