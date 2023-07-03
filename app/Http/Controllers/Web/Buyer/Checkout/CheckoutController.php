<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Buyer\Checkout;

use App\Contracts\Payment\PaymentFactoryInterface;
use App\Domain\Carts\DataTransferObjects\StoreCheckoutData;
use App\Domain\Orders\Actions\StoreOrderAction;
use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Actions\StorePaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\StoreRequest;
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

        $products = $cart->products->map(fn ($product) => [
            'name' => $product->name,
            'price' => number_format(num: $product->price, decimal_separator: ',', thousands_separator: '.'),
            'quantity' => $product->pivot->quantity,
            'sub_total' => number_format(num: $product->total, decimal_separator: ',', thousands_separator: '.'),
        ]);

        $total = number_format(num: $cart->total, decimal_separator: ',', thousands_separator: '.');

        return view(view: 'buyer.checkout.form', data: [
            'user' => request()->user(),
            'products' => $products,
            'total' => $total,
            'order' => request()->input(key: 'order')
        ]);
    }

    public function store(StoreRequest $request, PaymentFactoryInterface $paymentFactory): JsonResponse
    {
        try {
            $data = StoreCheckoutData::fromRequest($request);

            $order = Order::find($data->orderId);

            if (!isset($order)) {
                $order = (new StoreOrderAction())->execute();
            }

            $paymentProcessor = $paymentFactory->buildPaymentGateway(provider: $data->paymentMethod);

            $response = $paymentProcessor->makePaymentProcessData(
                data: $data,
                order: $order
            );

            (new StorePaymentAction())->execute(
                order: $order,
                provider: $data->paymentMethod,
                dataProvider: $response
            );

            return $this->successResponse(
                data: ['process_url' => $paymentProcessor->decodeProcessUrl($response)]
            );
        } catch (Throwable $exception) {
            report($exception);

            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.unavailable_service')]]
            );
        }
    }
}
