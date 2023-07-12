<?php

namespace Tests\Feature\Http\Controllers\Api\Product;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Models\Permission;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreProductControllerTest extends TestCase
{

    use RefreshDatabase;

    private User $user;

    private array $payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->make();

        $imagePath = tempnam(directory: sys_get_temp_dir(), prefix: 'images');
        $imageContent = file_get_contents(filename: 'https://via.placeholder.com/150');
        file_put_contents($imagePath, $imageContent);

        $image = new UploadedFile($imagePath, originalName: 'image.jpg', mimeType: null, error: null, test: true);

        $this->payload = [
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'status' => $product->status,
            'images' => [$image]
        ];
    }

    public function test_guest_user_get_unauthorized_response(): void
    {
        $this->postJson(route(name: 'api.products.store'), $this->payload)
            ->assertUnauthorized();
    }

    public function test_authorized_user_can_store_a_product(): void
    {
        $this->user->givePermissionTo(Permission::create(['name' => Permissions::PRODUCTS_CREATE]));

        Sanctum::actingAs($this->user);

        $this->postJson(route(name: 'api.products.store'), $this->payload)
            ->assertCreated()
            ->assertJson(function (AssertableJson $json){
                $json->where(key: 'message', expected: trans(key: 'server.record_created'));
            });

        $this->assertDatabaseCount(table: 'products', count: 1);
    }

    public function test_authorized_user_can_not_store_a_product_when_payload_is_bad(): void
    {
        $this->user->givePermissionTo(Permission::create(['name' => Permissions::PRODUCTS_CREATE]));

        Sanctum::actingAs($this->user);

        $this->postJson(route(name: 'api.products.store'))
            ->assertUnprocessable();

        $this->assertDatabaseCount(table: 'products', count: 0);
    }

    public function test_unauthorized_user_can_not_store_a_product(): void
    {
        Sanctum::actingAs($this->user);

        $this->postJson(route(name: 'api.products.store'), $this->payload)
            ->assertForbidden();

        $this->assertDatabaseCount(table: 'products', count: 0);
    }



}
