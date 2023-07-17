<?php

namespace Tests\Feature\Http\Controllers\Web\Buyer\Product;

use App\Domain\Carts\Models\Cart;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AddProductCartControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $product;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    public function test_guest_user_obtain_unauthorized_response(): void
    {
        $this->postJson(route(name: 'buyer.products.add.to.cart', parameters: ['product' => $this->product->id]))
            ->assertUnauthorized();
    }

    public function test_authorized_user_can_add_product_to_cart(): void
    {
        $this->user->assignRole(role: Roles::BUYER);

        $this->actingAs($this->user)
            ->postJson(route(name: 'buyer.products.add.to.cart', parameters: ['product' => $this->product->id]))
            ->assertOk();

        $this->assertDatabaseHas(table: 'carts', data: [
            'user_id' => $this->user->id
        ]);

        $this->assertDatabaseHas(table: 'productables', data: [
            'product_id' => $this->product->id,
            'quantity' => 1,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'productable_type' => Cart::class,
            'productable_id' => $this->user->cart->id,
        ]);
    }

    public function test_authorized_user_can_not_add_product_to_cart_when_it_not_enough_stock(): void
    {
        $this->product = Product::factory()->create([
            'stock' => 0
        ]);

        $this->user->assignRole(role: Roles::BUYER);

        $this->actingAs($this->user)
            ->postJson(route(name: 'buyer.products.add.to.cart', parameters: ['product' => $this->product->id]))
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                $json->where(key: 'message', expected: trans(key: 'validation.custom.product.with_out_stock'));
            });

        $this->assertDatabaseEmpty(table: 'productables');
    }

    public function test_unauthorized_user_can_not_add_product_to_cart(): void
    {
        $this->actingAs($this->user)
            ->postJson(route(name: 'buyer.products.add.to.cart', parameters: ['product' => $this->product->id]))
            ->assertForbidden();
    }
}
