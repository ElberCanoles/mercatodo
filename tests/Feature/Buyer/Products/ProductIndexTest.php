<?php

namespace Tests\Feature\Buyer\Products;

use App\Enums\User\RoleType;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    private User $buyer;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->buyer = User::factory()->create()->assignRole(RoleType::BUYER);
    }

    public function test_buyer_can_access_to_products_list_screen(): void
    {
        $response = $this
            ->actingAs($this->buyer)
            ->get(route(name: 'buyer.products.index'));

        $response->assertOk()
            ->assertViewIs(value: 'buyer.products.index');
    }

    public function test_guest_can_not_access_to_products_list_screen(): void
    {
        $response = $this
            ->get(route(name: 'buyer.products.index'));

        $response->assertRedirect(route(name: 'login'));
    }

    public function test_buyer_can_get_products_list_paginated_data(): void
    {
        $response = $this
            ->actingAs($this->buyer)
            ->getJson(route(name: 'buyer.products.index'));

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
