<?php
declare(strict_types=1);

namespace App\Actions\Cart;

use App\Exceptions\ProductExceptions;
use App\Models\Product;
use App\Services\Buyer\CartService;
use Symfony\Component\HttpFoundation\Cookie;

class AddItemAction
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    /**
     * @param Product $product
     * @return Cookie
     * @throws ProductExceptions
     */
    public function execute(Product $product): Cookie
    {
        $cart = $this->cartService->getFromCookieOrCreate();

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
            ],
        ]);

        $cart->touch();

        return $this->cartService->makeCookie($cart);
    }
}
