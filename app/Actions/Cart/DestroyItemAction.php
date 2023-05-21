<?php
declare(strict_types=1);

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Services\Buyer\CartService;
use Symfony\Component\HttpFoundation\Cookie;

class DestroyItemAction
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    /**
     * @param Product $product
     * @param Cart $cart
     * @return Cookie
     */
    public function execute(Product $product, Cart $cart): Cookie
    {
        $cart->products()->detach($product->id);

        $cart->touch();

        return $this->cartService->makeCookie($cart);
    }
}
