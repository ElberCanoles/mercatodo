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

class DestroyProductControllerTest extends TestCase
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
        $this->deleteJson(route(name: 'api.products.destroy', parameters: ['product' => $this->product->id]))
            ->assertUnauthorized();
    }

    public function test_authorized_user_can_delete_a_product(): void
    {
        $this->user->givePermissionTo(Permission::create(['name' => Permissions::PRODUCTS_DELETE]));

        Sanctum::actingAs($this->user);

        $this->deleteJson(route(name: 'api.products.destroy', parameters: ['product' => $this->product->id]))
            ->assertOk();

        $this->assertSoftDeleted($this->product);
    }

    public function test_authorized_user_get_not_found_exception_when_product_not_exists(): void
    {
        $this->user->givePermissionTo(Permission::create(['name' => Permissions::PRODUCTS_DELETE]));

        Sanctum::actingAs($this->user);

        $this->deleteJson(route(name: 'api.products.destroy', parameters: ['product' => 0]))
            ->assertNotFound()
            ->assertJson(function (AssertableJson $json) {
                $json->where(key: 'message', expected: trans(key: 'server.not_found'));
            });
    }

    public function test_unauthorized_user_can_not_delete_a_product(): void
    {
        Sanctum::actingAs($this->user);

        $this->deleteJson(route(name: 'api.products.destroy', parameters: ['product' => $this->product->id]))
            ->assertForbidden();
    }

}
