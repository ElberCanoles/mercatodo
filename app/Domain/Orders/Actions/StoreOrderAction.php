<?php

declare(strict_types=1);

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Models\Order;

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
