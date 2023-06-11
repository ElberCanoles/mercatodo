<?php

declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Checkout;

use App\Actions\Order\StoreOrderAction;
use App\Actions\Payment\StorePaymentAction;
use App\Contracts\Payment\PaymentFactoryInterface;
use App\DataTransferObjects\Checkout\StoreCheckoutData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\StoreRequest;
use App\Models\Order;
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
            'sub_total' => number_format(num: $product->total, decimal_separator: ',', thousands_separator: '.')
        ]);

        $total = number_format(num: $cart->total, decimal_separator: ',', thousands_separator: '.');

        return view(view: 'buyer.checkout.form', data: [
            'user' => request()->user(),
            'products' => $products,
            'total' => $total
        ]);
    }

    public function store(StoreRequest $request, PaymentFactoryInterface $paymentFactory): JsonResponse
    {
        try {
            $order = Order::query()->getLatestPendingForUser($request->user())->first();

            $data = StoreCheckoutData::fromRequest($request);

            if (!isset($order)) {
                $order = (new StoreOrderAction())->execute();
            }

            $paymentProcessor = $paymentFactory->buildPaymentGateway(provider: $data->paymentMethod);

            $response = $paymentProcessor->getPaymentProcessData(
                data: $data,
                reference: $order->id,
                amount: $order->amount
            );

            (new StorePaymentAction())->execute(
                order: $order,
                provider: $data->paymentMethod,
                dataProvider: $response
            );

            return $this->successResponse(
                data: ['process_url' => $paymentProcessor->getProcessUrl($response)]
            );
        } catch (Throwable $exception) {
            report($exception);

            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.unavailable_service')]]
            );
        }
    }
}
