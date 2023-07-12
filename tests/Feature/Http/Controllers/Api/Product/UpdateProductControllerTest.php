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

class UpdateProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $product;

    private array $payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();

        /**
         * @var Product $newProductData
         */
        $newProductData = Product::factory()->make();

        $this->payload = [
            'name' => $newProductData->name,
            'description' => $newProductData->description,
            'price' => $newProductData->price,
            'stock' => $newProductData->stock,
            'status' => $newProductData->status,
            'preloaded_images' => [['path' => 'https://test.com/fake-storage/image.png']]
        ];
    }

    public function test_guest_user_get_unauthorized_response(): void
    {
        $this->putJson(route(name: 'api.products.update', parameters: ['product' => $this->product->id]), $this->payload)
            ->assertUnauthorized();
    }

    public function test_authorized_user_can_update_a_product(): void
    {
        $this->user->givePermissionTo(Permission::create(['name' => Permissions::PRODUCTS_UPDATE]));

        Sanctum::actingAs($this->user);

        $this->putJson(route(name: 'api.products.update', parameters: ['product' => $this->product->id]), $this->payload)
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->where(key: 'message', expected: trans(key: 'server.record_updated'));
            });

        $this->product->refresh();

        $this->assertSame(expected: $this->payload['name'], actual: $this->product->name);
        $this->assertSame(expected: $this->payload['description'], actual: $this->product->description);
        $this->assertSame(expected: (float)$this->payload['price'], actual: $this->product->price);
        $this->assertSame(expected: $this->payload['stock'], actual: $this->product->stock);
        $this->assertSame(expected: $this->payload['status'], actual: $this->product->status);
    }

    public function test_authorized_user_get_not_found_exception_when_product_not_exists(): void
    {
        $this->user->givePermissionTo(Permission::create(['name' => Permissions::PRODUCTS_UPDATE]));

        Sanctum::actingAs($this->user);

        $this->putJson(route(name: 'api.products.update', parameters: ['product' => 0]), $this->payload)
            ->assertNotFound()
            ->assertJson(function (AssertableJson $json) {
                $json->where(key: 'message', expected: trans(key: 'server.not_found'));
            });
    }

    public function test_authorized_user_can_not_update_a_product_when_payload_is_bad(): void
    {
        $this->user->givePermissionTo(Permission::create(['name' => Permissions::PRODUCTS_UPDATE]));

        Sanctum::actingAs($this->user);

        $this->putJson(route(name: 'api.products.update', parameters: ['product' => $this->product->id]))
            ->assertUnprocessable();
    }

    public function test_unauthorized_user_can_not_update_a_product(): void
    {
        Sanctum::actingAs($this->user);

        $this->putJson(route(name: 'api.products.update', parameters: ['product' => $this->product->id]), $this->payload)
            ->assertForbidden();
    }

}
