<?php
declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Checkout;

use App\Actions\Order\StoreOrderAction;
use App\Actions\Payment\StorePaymentAction;
use App\DataTransferObjects\Checkout\StoreCheckoutData;
use App\Enums\Payment\Provider;
use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\StoreRequest;
use App\Services\Payments\PlaceToPay;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Throwable;

class CheckoutController extends Controller
{
    use MakeJsonResponse;

    public function create(): View
    {
        $cart = request()->user()->cart;

        $products = $cart->products->map(fn($product) => [
            'name' => $product->name,
            'price' => number_format(num: $product->price, decimal_separator: ',', thousands_separator: '.'),
            'quantity' => $product->pivot->quantity,
            'sub_total' => number_format(num: $product->total, decimal_separator: ',', thousands_separator: '.')
        ]);

        $total = number_format(num: $cart->total, decimal_separator: ',', thousands_separator: '.');

        return view(view: 'buyer.checkout.form', data: [
            'user' => request()->user(),
            'products' => $products,
            'total' => $total
        ]);
    }

    public function store(StoreRequest $request, StoreOrderAction $storeOrderAction, StorePaymentAction $storePaymentAction): JsonResponse
    {
        try {

            $order = $storeOrderAction->execute();

            $storePaymentAction->execute(
                order: $order,
                provider: Provider::PLACE_TO_PAY
            );

            $placeToPay = new PlaceToPay();

            return $this->successResponse(
                data: ['process_url' => $placeToPay->pay(
                    data: StoreCheckoutData::fromRequest($request),
                    paymentReference: $order->id,
                    amount: $order->amount
                )]
            );
        } catch (Throwable $exception) {

            report($exception);

            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.unavailable_service')]]
            );
        }
    }
}
