<?php

declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Payment;

use App\Enums\Order\OrderStatus;
use App\Factories\PlaceToPay\PlaceToPayPaymentActionsFactory;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payments\PlaceToPay\PlaceToPayService;
use Illuminate\Http\RedirectResponse;
use Throwable;

class PlaceToPayController extends Controller
{
    public function __construct(private readonly PlaceToPayService $placeToPayService)
    {
    }

    public function processResponse(): RedirectResponse
    {
        try {

            $order = Order::query()->where(column: 'user_id', operator: '=', value: auth()->user()->id)
                ->where(column: 'status', operator: '=', value: OrderStatus::PENDING)
                ->latest()
                ->first();

            $status = $this->placeToPayService->getSession($order->payments()->latest()->first()->data_provider['requestId'])['status']['status'];

            $checkPaymentActions = (new PlaceToPayPaymentActionsFactory($this->placeToPayService))->make();

            foreach ($checkPaymentActions as $checkPaymentAction)
            {
                $checkPaymentAction($status, $order);
            }

        } catch (Throwable $exception) {
            report($exception);
        }

        return redirect()->to(path: route(name: 'buyer.checkout.result'));
    }

    public function abortSession(): RedirectResponse
    {
        $order = Order::query()->where(column: 'user_id', operator: '=', value: auth()->user()->id)
            ->where(column: 'status', operator: '=', value: OrderStatus::PENDING)
            ->latest()
            ->first();

        $order->cancelled();
        $order->payments()->latest()->first()->rejected();

        return redirect()->to(path: route(name: 'buyer.checkout.result'));
    }
}
