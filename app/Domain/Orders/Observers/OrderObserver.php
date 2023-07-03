<?php

declare(strict_types=1);

namespace App\Domain\Orders\Observers;

use App\Domain\Orders\Models\Order;

class OrderObserver
{
    public function created(Order $order): void
    {
        $cart = auth()->user()->cart;

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
