<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Buyer\Cart;

use App\Http\Controllers\Controller;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    use MakeJsonResponse;

    public function index(Request $request): View|JsonResponse
    {
        if (!$request->wantsJson()) {
            return view(view: 'buyer.cart.index');
        }

        $cart = $request->user()->cart;

        $products = $cart->products->map(fn ($product) => [
            'name' => $product->name,
            'price' => number_format(num: $product->price, decimal_separator: ',', thousands_separator: '.'),
            'quantity' => $product->pivot->quantity,
            'sub_total' => number_format(num: $product->total, decimal_separator: ',', thousands_separator: '.'),
            'add_to_cart_url' => route(name: 'buyer.products.add.to.cart', parameters: ['product' => $product->id]),
            'less_to_cart_url' => route(name: 'buyer.products.less.to.cart', parameters: ['product' => $product->id]),
            'remove_from_cart_url' => route(name: 'buyer.products.carts.destroy', parameters: ['product' => $product->id])
        ]);

        $total = number_format(num: $cart->total, decimal_separator: ',', thousands_separator: '.');

        return $this->successResponse(data: ['products' => $products, 'total' => $total]);
    }
}
