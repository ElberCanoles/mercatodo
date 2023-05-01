<?php

namespace Tests\Feature\Buyer\Products;

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

    private User $buyer;

    private Product $productAvailable;

    private Product $productUnavailable;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->buyer = User::factory()->create()->assignRole(RoleType::BUYER);
        $this->productAvailable = Product::factory()->create(['status' => ProductStatus::AVAILABLE]);
        $this->productUnavailable = Product::factory()->create(['status' => ProductStatus::UNAVAILABLE]);
    }

    public function test_buyer_can_access_to_products_show_screen_when_is_available(): void
    {
        $response = $this
            ->actingAs($this->buyer)
            ->get(route(name: 'buyer.products.show', parameters: ['slug' => $this->productAvailable->slug]));

        $response->assertOk()
            ->assertViewIs(value: 'buyer.products.show')
            ->assertViewHas(key: 'product', value: $this->productAvailable);
    }

    public function test_buyer_can_not_access_to_products_show_screen_when_is_unavailable(): void
    {
        $response = $this
            ->actingAs($this->buyer)
            ->get(route(name: 'buyer.products.show', parameters: ['slug' => $this->productUnavailable->slug]));

        $response->assertNotFound();
    }

    public function test_guest_can_not_access_to_products_show_screen(): void
    {
        $response = $this
            ->get(route(name: 'buyer.products.show', parameters: ['slug' => $this->productAvailable->slug]));

        $response->assertRedirect(route(name: 'login'));
    }
}
