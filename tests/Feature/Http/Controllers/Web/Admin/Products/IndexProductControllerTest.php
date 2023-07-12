<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Products;

use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\Permission;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole(role: Roles::ADMINISTRATOR);
        $this->admin->givePermissionTo(permission: Permission::create(['name' => Permissions::PRODUCTS_INDEX]));
    }

    public function test_admin_can_access_to_products_list_screen(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route(name: 'admin.products.index'));

        $response->assertOk()
            ->assertViewIs(value: 'admin.products.index');
    }

    public function test_admin_can_get_products_list_paginated_data(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->getJson(route(name: 'admin.products.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [],
                'links' => [],
                'meta' => [
                    "current_page",
                    "from",
                    "last_page",
                    "links" => [
                    ],
                    "path",
                    "per_page",
                    "to",
                    "total"
                ]
            ]);
    }
}
