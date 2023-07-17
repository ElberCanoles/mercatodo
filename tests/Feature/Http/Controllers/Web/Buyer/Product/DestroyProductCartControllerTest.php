<?php

namespace Tests\Feature\Http\Controllers\Web\Buyer\Product;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyProductCartControllerTest extends TestCase
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
        $this->deleteJson(route(name: 'buyer.products.carts.destroy', parameters: ['product' => $this->product->id]))
            ->assertUnauthorized();
    }

    public function test_authorized_user_can_destroy_product_from_cart(): void
    {
        $this->user->assignRole(role: Roles::BUYER);
        $this->user->cart->products()->syncWithoutDetaching([
            $this->product->id => [
                'quantity' => 5,
                'name' => $this->product->name,
                'price' => $this->product->price
            ]
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route(name: 'buyer.products.carts.destroy', parameters: ['product' => $this->product->id]))
            ->assertOk();

        $this->assertDatabaseHas(table: 'carts', data: [
            'user_id' => $this->user->id
        ]);

        $this->assertDatabaseEmpty(table: 'productables');
    }

    public function test_unauthorized_user_can_not_destroy_product_from_cart(): void
    {
        $this->actingAs($this->user)
            ->deleteJson(route(name: 'buyer.products.carts.destroy', parameters: ['product' => $this->product->id]))
            ->assertForbidden();
    }
}
