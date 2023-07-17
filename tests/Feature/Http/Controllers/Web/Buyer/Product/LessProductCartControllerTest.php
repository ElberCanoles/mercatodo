<?php

namespace Tests\Feature\Http\Controllers\Web\Buyer\Product;

use App\Domain\Carts\Models\Cart;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessProductCartControllerTest extends TestCase
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
        $this->postJson(route(name: 'buyer.products.less.to.cart', parameters: ['product' => $this->product->id]))
            ->assertUnauthorized();
    }

    public function test_authorized_user_can_less_product_from_cart(): void
    {
        $this->user->assignRole(role: Roles::BUYER);
        $this->user->cart->products()->syncWithoutDetaching([
            $this->product->id => [
                'quantity' => 2,
                'name' => $this->product->name,
                'price' => $this->product->price
            ]
        ]);

        $this->actingAs($this->user)
            ->postJson(route(name: 'buyer.products.less.to.cart', parameters: ['product' => $this->product->id]))
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

    public function test_product_is_removed_from_cart_when_current_quantity_is_one(): void
    {
        $this->user->assignRole(role: Roles::BUYER);
        $this->user->cart->products()->syncWithoutDetaching([
            $this->product->id => [
                'quantity' => 1,
                'name' => $this->product->name,
                'price' => $this->product->price
            ]
        ]);

        $this->actingAs($this->user)
            ->postJson(route(name: 'buyer.products.less.to.cart', parameters: ['product' => $this->product->id]))
            ->assertOk();

        $this->assertDatabaseHas(table: 'carts', data: [
            'user_id' => $this->user->id
        ]);

        $this->assertDatabaseEmpty(table: 'productables');
    }

    public function test_unauthorized_user_can_not_lest_product_from_cart(): void
    {
        $this->actingAs($this->user)
            ->postJson(route(name: 'buyer.products.less.to.cart', parameters: ['product' => $this->product->id]))
            ->assertForbidden();
    }
}
