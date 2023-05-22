<?php
declare(strict_types=1);

namespace App\Http\Controllers\Guest\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Services\Buyer\CartService;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    use MakeJsonResponse;

    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if (!$request->wantsJson()) {
            return view(view: 'guest.cart.index');
        }

        $cart = $this->cartService->getFromCookie();

        if (isset($cart)) {
            $products = Cart::query()
                ->with(relations: 'products')
                ->where(column: 'id', operator: '=', value: $cart->id)
                ->first()
                ->products->map(fn($product) => [
                    'name' => $product->name,
                    'price' => number_format(num: $product->price, decimal_separator: ',', thousands_separator: '.'),
                    'quantity' => $product->pivot->quantity,
                    'sub_total' => number_format(num: $product->total, decimal_separator: ',', thousands_separator: '.'),
                    'add_to_cart_url' => route(name: 'products.add.to.cart',parameters: ['product' => $product->id]),
                    'less_to_cart_url' => route(name: 'products.less.to.cart',parameters: ['product' => $product->id]),
                    'remove_from_cart_url' => route(name: 'products.carts.destroy',parameters: ['product' => $product->id, 'cart' => $cart->id])
                ]);

            $total = number_format(num: $cart->getTotalAttribute(), decimal_separator: ',', thousands_separator: '.');

            return $this->successResponse(data: ['products' => $products, 'total' => $total]);
        } else {
            return $this->successResponse(data: ['products' => [], 'total' => 0]);
        }
    }
}
