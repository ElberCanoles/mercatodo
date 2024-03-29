<?php

namespace Tests\Feature\Http\Controllers\Web\Guest\Product;

use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowProductControllerTest extends TestCase
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
