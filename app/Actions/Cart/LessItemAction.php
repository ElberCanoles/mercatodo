<?php
declare(strict_types=1);

namespace App\Actions\Cart;

use App\Models\Product;
use App\Services\Buyer\CartService;
use Symfony\Component\HttpFoundation\Cookie;

class LessItemAction
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    /**
     * @param Product $product
     * @return Cookie
     */
    public function execute(Product $product): Cookie
    {
        $cart = $this->cartService->getFromCookieOrCreate();

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

        return $this->cartService->makeCookie($cart);
    }

}
