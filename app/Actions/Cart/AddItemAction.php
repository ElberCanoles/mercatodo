<?php

declare(strict_types=1);

namespace App\Actions\Cart;

use App\Exceptions\ProductExceptions;
use App\Models\Cart;
use App\Models\Product;

class AddItemAction
{
    /**
     * @param Product $product
     * @param Cart $cart
     * @return void
     * @throws ProductExceptions
     */
    public function execute(Product $product, Cart $cart): void
    {
        $quantity = $cart->products()
            ->find($product->id)
            ->pivot
            ->quantity ?? 0;

        if ($product->stock < $quantity + 1) {
            throw ProductExceptions::productWithOutStock();
        }

        $cart->products()->syncWithoutDetaching([
            $product->id => [
                'quantity' => $quantity + 1,
                'name' => $product->name,
                'price' => $product->price
            ]
        ]);

        $cart->touch();
    }
}
