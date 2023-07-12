<?php

namespace Tests\Feature\Http\Controllers\Api\Product;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Models\Permission;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $product;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    public function test_guest_user_get_unauthorized_response(): void
    {
        $this->getJson(route(name: 'api.products.show', parameters: ['product' => $this->product->id]))
            ->assertUnauthorized();
    }

    public function test_authorized_user_can_show_a_product(): void
    {
        $this->user->givePermissionTo(Permission::create(['name' => Permissions::PRODUCTS_SHOW]));

        Sanctum::actingAs($this->user);

        $this->getJson(route(name: 'api.products.show', parameters: ['product' => $this->product->id]))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function (AssertableJson $data) {
                    $data->has('id')
                        ->has('name')
                        ->has('price')
                        ->has('stock')
                        ->has('status_key')
                        ->has('status_value')
                        ->has('description')
                        ->has('images')
                        ->has('created_at')
                        ->has('show_url')
                        ->has('update_url')
                        ->has('delete_url');
                });
            });
    }

    public function test_authorized_user_get_not_found_exception_when_product_not_exists(): void
    {
        $this->user->givePermissionTo(Permission::create(['name' => Permissions::PRODUCTS_SHOW]));

        Sanctum::actingAs($this->user);

        $this->getJson(route(name: 'api.products.show', parameters: ['product' => 0]))
            ->assertNotFound()
            ->assertJson(function (AssertableJson $json) {
                $json->where(key: 'message', expected: trans(key: 'server.not_found'));
            });
    }

    public function test_unauthorized_user_can_not_show_a_product(): void
    {
        Sanctum::actingAs($this->user);

        $this->getJson(route(name: 'api.products.show', parameters: ['product' => $this->product->id]))
            ->assertForbidden();
    }

}
