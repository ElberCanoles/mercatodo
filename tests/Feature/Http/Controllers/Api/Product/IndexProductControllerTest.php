<?php

namespace Tests\Feature\Http\Controllers\Api\Product;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Models\Permission;
use App\Domain\Users\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_user_get_unauthorized_response(): void
    {
        $this->getJson(route(name: 'api.products.index'))
            ->assertUnauthorized();
    }

    public function test_authorized_user_can_call_index(): void
    {
        Product::factory(count: 5)->create();
        $this->user->givePermissionTo(Permission::create(['name' => Permissions::PRODUCTS_INDEX]));

        Sanctum::actingAs($this->user);

        $this->getJson(route(name: 'api.products.index'))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->count(key: 'data', length: 5)->has('data.0', function (AssertableJson $data) {
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
                })->etc();
            });
    }

    public function test_unauthorized_user_can_not_call_index(): void
    {
        Product::factory(count: 5)->create();

        Sanctum::actingAs($this->user);

        $this->getJson(route(name: 'api.products.index'))
            ->assertForbidden();
    }
}
