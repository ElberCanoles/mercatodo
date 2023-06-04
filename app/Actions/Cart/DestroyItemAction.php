<?php
declare(strict_types=1);

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\Product;

class DestroyItemAction
{
    /**
     * @param Product $product
     * @param Cart $cart
     * @return void
     */
    public function execute(Product $product, Cart $cart): void
    {
        $cart->products()->detach($product->id);

        $cart->touch();
    }
}
