<?php

declare(strict_types=1);

namespace App\Factories\PlaceToPay;

use App\Domain\Orders\Models\Order;
use App\Services\Payments\PlaceToPay\PlaceToPayService;

class PlaceToPayPaymentActionsFactory
{
    public function __construct(private readonly PlaceToPayService $placeToPayService)
    {
    }

    public function make(): array
    {
        return [
            function (string $status, Order $order) {
                if ($this->placeToPayService->paymentIsPending(status: $status)) {
                    $order->pending();
                    $order->payments()->latest()->first()->pending();
                }
            },

            function (string $status, Order $order) {
                if ($this->placeToPayService->paymentIsApproved(status: $status)) {
                    $order->confirmed();
                    $order->payments()->latest()->first()->paid();
                }
            },

            function (string $status, Order $order) {
                if ($this->placeToPayService->paymentIsRejected(status: $status)) {
                    $order->cancelled();
                    $order->payments()->latest()->first()->rejected();
                }
            }
        ];
    }
}
