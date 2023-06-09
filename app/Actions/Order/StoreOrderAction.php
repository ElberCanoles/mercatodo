<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Order;

class StoreOrderAction
{
    public function execute(): Order
    {
        $user = auth()->user();

        return $user->orders()->create([
            'amount' => $user->cart->total
        ]);
    }
}
