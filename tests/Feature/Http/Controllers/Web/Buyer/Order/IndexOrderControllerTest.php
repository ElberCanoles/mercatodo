<?php

namespace Tests\Feature\Http\Controllers\Web\Buyer\Order;

use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\Permission;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class IndexOrderControllerTest extends TestCase
{

    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->user = User::factory()->create();
    }

    public function test_guest_user_is_redirected_to_login(): void
    {
        $this->get(route(name: 'buyer.orders.index'))
            ->assertRedirect(uri: '/login');
    }

    public function test_authorized_user_can_access_to_orders_screen(): void
    {
        $this->user->assignRole(role: Roles::BUYER);
        $this->user->givePermissionTo(Permission::create([
            'name' => Permissions::ORDERS_INDEX
        ]));

        $this->actingAs($this->user)
            ->get(route(name: 'buyer.orders.index'))
            ->assertOk()
            ->assertViewIs(value: 'buyer.order.index');
    }

    public function test_authorized_user_can_get_orders_list(): void
    {
        $this->user->assignRole(role: Roles::BUYER);
        $this->user->givePermissionTo(Permission::create([
            'name' => Permissions::ORDERS_INDEX
        ]));

        DB::table(table: 'orders')->insert([
            'user_id' => $this->user->id,
            'amount' => rand(10, 100),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->actingAs($this->user)
            ->getJson(route(name: 'buyer.orders.index'))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->count(key: 'data', length: 1)->has('data.0', function (AssertableJson $data) {
                    $data->has('id')
                        ->has('amount')
                        ->has('status_key')
                        ->has('status_value')
                        ->has('created_at')
                        ->has('show_url');
                })->etc();
            });
    }

    public function test_unauthorized_user_can_not_access_to_orders_screen(): void
    {
        $this->actingAs($this->user)
            ->get(route(name: 'buyer.orders.index'))
            ->assertForbidden();
    }

    public function test_unauthorized_user_can_not_get_orders_list(): void
    {
        $this->actingAs($this->user)
            ->getJson(route(name: 'buyer.orders.index'))
            ->assertForbidden();
    }

}
