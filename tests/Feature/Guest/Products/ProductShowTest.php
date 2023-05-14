<?php

namespace Tests\Feature\Guest\Products;

use App\Enums\Product\ProductStatus;
use App\Enums\User\RoleType;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductShowTest extends TestCase
{
    use RefreshDatabase;

    private Product $productAvailable;

    private Product $productUnavailable;

    public function setUp(): void
    {
        parent::setUp();
        $this->productAvailable = Product::factory()->create(['status' => ProductStatus::AVAILABLE]);
        $this->productUnavailable = Product::factory()->create(['status' => ProductStatus::UNAVAILABLE]);
    }

    public function test_any_user_can_access_to_products_show_screen_when_is_available(): void
    {
        $response = $this
            ->get(route(name: 'products.show', parameters: ['slug' => $this->productAvailable->slug]));

        $response->assertOk()
            ->assertViewIs(value: 'guest.products.show')
            ->assertViewHas(key: 'product', value: $this->productAvailable);
    }

    public function test_any_user_can_not_access_to_products_show_screen_when_is_unavailable(): void
    {
        $response = $this
            ->get(route(name: 'products.show', parameters: ['slug' => $this->productUnavailable->slug]));

        $response->assertNotFound();
    }
}
