<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
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

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
