<?php

namespace Tests\Feature\Http\Controllers\Web\Guest\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexProductControllerTest extends TestCase
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
