<?php

namespace Tests\Unit\Domain\Orders\Actions;

use App\Domain\Orders\Actions\StoreOrderAction;
use App\Domain\Orders\Models\Order;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class StoreOrderActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_execute_it_returns_an_order(): void
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        Auth::login($user);

        $storeOrderAction = new StoreOrderAction();

        $order = $storeOrderAction->execute();

        $this->assertInstanceOf(expected: Order::class, actual: $order);
        $this->assertDatabaseCount(table: 'orders', count: 1);
        $this->assertDatabaseHas(table: 'orders', data: [
            'id' => $order->id,
            'user_id' => $user->id,
            'amount' => $order->amount
        ]);
    }

}
