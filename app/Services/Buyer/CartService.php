<?php

declare(strict_types=1);

namespace App\Services\Buyer;

use App\Models\Cart;
use Illuminate\Support\Facades\Cookie;

class CartService
{
    protected string $cookieName;
    protected int $cookieExpiration;

    public function __construct()
    {
        $this->cookieName = config(key: 'cart.cookie.name');
        $this->cookieExpiration = config(key: 'cart.cookie.expiration');
    }

    public function getFromCookie(): ?Cart
    {
        $cartId = Cookie::get($this->cookieName);

        return Cart::find($cartId);
    }

    public function getFromCookieOrCreate(): Cart
    {
        $cart = $this->getFromCookie();

        return $cart ?? Cart::create();
    }

    public function makeCookie(Cart $cart): \Symfony\Component\HttpFoundation\Cookie
    {
        return Cookie::make(
            name: $this->cookieName,
            value: (string)$cart->id,
            minutes: $this->cookieExpiration
        );
    }

    public function countProducts()
    {
        $cart = $this->getFromCookie();

        if ($cart != null) {
            return $cart->products()->pluck(column: 'pivot.quantity')->sum();
        }

        return 0;
    }
}
