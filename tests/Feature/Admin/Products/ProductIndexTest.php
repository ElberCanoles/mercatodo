<?php

namespace Tests\Feature\Admin\Products;

use App\Enums\User\RoleType;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->admin = User::factory()->create()->assignRole(RoleType::ADMINISTRATOR);
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
                'current_page',
                'data' => [],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links' => [],
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ]);
    }
}
