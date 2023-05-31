<?php
declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Checkout;

use App\DataTransferObjects\Checkout\StoreCheckoutData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\StoreRequest;
use App\Models\Cart;
use App\Services\Buyer\CartService;
use App\Services\Payments\PlaceToPay;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    use MakeJsonResponse;

    public function __construct(private readonly CartService $cartService)
    {
    }

    public function create(): View
    {
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
                    'sub_total' => number_format(num: $product->total, decimal_separator: ',', thousands_separator: '.')
                ]);

            $total = number_format(num: $cart->total, decimal_separator: ',', thousands_separator: '.');
        }

        return view(view: 'buyer.checkout.form', data: [
            'user' => request()->user(),
            'products' => $products ?? [],
            'total' => $total ?? 0
        ]);
    }

    public function store(StoreRequest $request, PlaceToPay $placeToPay): JsonResponse
    {
        try {
            return $this->successResponse(
                data: ['process_url' => $placeToPay->pay(StoreCheckoutData::fromRequest($request))]
            );
        } catch (\Throwable $exception) {

            report($exception);

            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.unavailable_service')]]
            );
        }
    }
}
