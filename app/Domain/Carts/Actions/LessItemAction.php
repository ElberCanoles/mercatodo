<?php

declare(strict_types=1);

namespace App\Domain\Carts\Actions;

use App\Domain\Carts\Models\Cart;
use App\Domain\Products\Models\Product;

class LessItemAction
{
    /**
     * @param Product $product
     * @param Cart $cart
     * @return void
     */
    public function execute(Product $product, Cart $cart): void
    {
        $quantity = $cart->products()
            ->find($product->id)
            ->pivot
            ->quantity ?? 0;

        if ($quantity == 1) {
            $cart->products()->detach($product->id);
        } else {
            $cart->products()->syncWithoutDetaching([
                $product->id => ['quantity' => $quantity - 1],
            ]);
        }

        $cart->touch();

        $cart->refresh();
    }
}
