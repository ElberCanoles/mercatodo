<?php

namespace Tests\Feature\Guest\Products;

use App\Enums\User\RoleType;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_any_user_can_access_to_products_list_screen(): void
    {
        $response = $this
            ->get(route(name: 'products.index'));

        $response->assertOk()
            ->assertViewIs(value: 'guest.products.index');
    }

    public function test_any_user_can_get_products_list_paginated_data(): void
    {
        $response = $this
            ->getJson(route(name: 'products.index'));

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
