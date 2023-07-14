<?php

namespace Tests\Feature\Http\Controllers\Web\Buyer\Order;

use App\Domain\Orders\Models\Order;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Models\Permission;
use App\Domain\Users\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ShowOrderControllerTest extends TestCase
{

    use RefreshDatabase;

    private User $firstUser;

    private User $secondUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(class: RoleSeeder::class);
        $this->firstUser = User::factory()->create();
        $this->secondUser = User::factory()->create();

        DB::table(table: 'orders')->insert([
            'user_id' => $this->firstUser->id,
            'amount' => rand(10, 100),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table(table: 'orders')->insert([
            'user_id' => $this->secondUser->id,
            'amount' => rand(10, 100),
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }

    public function test_guest_user_is_redirected_to_login(): void
    {
        $this->get(route(name: 'buyer.orders.show', parameters: ['order' => Order::first()->id]))
            ->assertRedirect(uri: '/login');
    }

    public function test_authorized_user_can_show_your_order(): void
    {
        $this->firstUser->assignRole(role: Roles::BUYER);
        $this->firstUser->givePermissionTo(Permission::create([
            'name' => Permissions::ORDERS_SHOW
        ]));

        /**
         * @var Order $order
         */
        $order = Order::query()->whereUser($this->firstUser)->first();

        $this->actingAs($this->firstUser)
            ->get(route(name: 'buyer.orders.show', parameters: ['order' => $order->id]))
            ->assertOk()
            ->assertViewIs(value: 'buyer.order.show')
            ->assertViewHas(key: 'order', value: $order);
    }

    public function test_authorized_user_can_not_show_order_of_another_user(): void
    {
        $this->firstUser->assignRole(role: Roles::BUYER);
        $this->firstUser->givePermissionTo(Permission::create([
            'name' => Permissions::ORDERS_SHOW
        ]));

        /**
         * @var Order $order
         */
        $order = Order::query()->whereUser($this->secondUser)->first();

        $this->actingAs($this->firstUser)
            ->get(route(name: 'buyer.orders.show', parameters: ['order' => $order->id]))
            ->assertForbidden();
    }

    public function test_unauthorized_user_can_not_show_any_order(): void
    {
        $this->secondUser->assignRole(role: Roles::BUYER);

        /**
         * @var Order $order
         */
        $order = Order::query()->whereUser($this->secondUser)->first();

        $this->actingAs($this->secondUser)
            ->get(route(name: 'buyer.orders.show', parameters: ['order' => $order->id]))
            ->assertForbidden();
    }


}
