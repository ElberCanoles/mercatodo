<?php

namespace Tests\Feature\Http\Controllers\Web\Buyer\Cart;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class IndexCartControllerTest extends TestCase
{

    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->user = User::factory()->create();
        Product::factory(count: 10)->create();

        /**
         * @var Product $product
         */
        foreach (Product::limit(5)->get() as $product) {
            $this->user->cart->products()->syncWithoutDetaching([
                $product->id => [
                    'quantity' => rand(1, 5),
                    'name' => $product->name,
                    'price' => $product->price
                ]
            ]);
        }
    }

    public function test_guest_user_is_redirected_to_login(): void
    {
        $this->get(route(name: 'buyer.cart.index'))
            ->assertRedirect(uri: '/login');
    }

    public function test_authorized_user_can_access_to_cart_screen(): void
    {
        $this->user->assignRole(role: Roles::BUYER);

        $this->actingAs($this->user)
            ->get(route(name: 'buyer.cart.index'))
            ->assertOk()
            ->assertViewIs(value: 'buyer.cart.index');

    }

    public function test_authorized_user_can_get_products_list_in_cart(): void
    {
        $this->user->assignRole(role: Roles::BUYER);

        $this->actingAs($this->user)
            ->getJson(route(name: 'buyer.cart.index'))
            ->assertOk()
            ->assertJsonCount(count: 5, key: 'products')
            ->assertJsonStructure([
                'products',
                'total'
            ]);

    }

    public function test_unauthorized_user_can_not_access_to_cart_screen(): void
    {
        $this->actingAs($this->user)
            ->get(route(name: 'buyer.cart.index'))
            ->assertForbidden();
    }

    public function test_unauthorized_user_can_not_get_products_list_in_cart(): void
    {
        $this->actingAs($this->user)
            ->getJson(route(name: 'buyer.cart.index'))
            ->assertForbidden();
    }

}
