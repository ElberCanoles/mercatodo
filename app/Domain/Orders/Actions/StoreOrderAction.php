<?php

declare(strict_types=1);

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Models\Order;
use App\Domain\Users\Models\User;

class StoreOrderAction
{
    public function execute(): Order
    {
        $user = User::find(auth()->user()->getAuthIdentifier());

        return Order::query()->create([
            'user_id' => $user->id,
            'amount' => $user->cart->total
        ]);
    }
}
