<?php

declare(strict_types=1);

namespace App\Domain\Orders\Observers;

use App\Domain\Orders\Models\Order;
use App\Domain\Users\Models\User;

class OrderObserver
{
    public function created(Order $order): void
    {
        $cart = User::query()->find(auth()->user()->getAuthIdentifier())->cart;

        foreach ($cart->products as $product) {
            $order->products()->syncWithoutDetaching([
                $product->id => [
                    'quantity' => $product->pivot->quantity,
                    'name' => $product->name,
                    'price' => $product->price
                ]
            ]);
        }

        $cart->products()->detach();
    }
}
