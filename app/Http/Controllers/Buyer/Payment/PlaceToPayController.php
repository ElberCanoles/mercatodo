<?php

declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Payment;

use App\Enums\Order\OrderStatus;
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

            $order = Order::query()
                ->where(column: 'user_id', operator: '=', value: auth()->user()->getAuthIdentifier())
                ->where(column: 'status', operator: '=', value: OrderStatus::PENDING)
                ->latest()
                ->first();

            $response = $this->placeToPayService->getSession($order->payments()->latest()->first()->data_provider['requestId']);

            $status = $response['status']['status'];

            if ($this->placeToPayService->paymentIsPending(status: $status)) {

                $order->pending();
                $order->payments()->latest()->first()->pending();
            } elseif ($this->placeToPayService->paymentIsApproved(status: $status)) {

                $order->confirmed();
                $order->payments()->latest()->first()->paid();
            } else {

                $order->cancelled();
                $order->payments()->latest()->first()->rejected();
            }
        } catch (Throwable $exception) {
            report($exception);
        }

        return redirect()->to(path: route(name: 'buyer.checkout.result'));
    }

    public function abortSession(): RedirectResponse
    {
        $order = Order::query()
            ->where(column: 'user_id', operator: '=', value: auth()->user()->getAuthIdentifier())
            ->where(column: 'status', operator: '=', value: OrderStatus::PENDING)
            ->latest()
            ->first();

        $order->cancelled();

        $order->payments()->latest()->first()->rejected();

        return redirect()->to(path: route(name: 'buyer.checkout.result'));
    }
}
