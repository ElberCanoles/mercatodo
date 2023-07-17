<?php

declare(strict_types=1);

namespace App\Domain\Payments\Factories\PlaceToPay;

use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Enums\Provider;
use App\Domain\Payments\Models\Payment;
use App\Domain\Payments\Services\PlaceToPay\PlaceToPayService;

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

                    Payment::query()->whereOrder(order: $order)
                        ->whereProvider(provider: Provider::PLACE_TO_PAY)
                        ->orderByDesc(column: 'created_at')
                        ->first()
                        ->pending();
                }
            },

            function (string $status, Order $order) {
                if ($this->placeToPayService->paymentIsApproved(status: $status)) {
                    $order->confirmed();

                    Payment::query()->whereOrder(order: $order)
                        ->whereProvider(provider: Provider::PLACE_TO_PAY)
                        ->orderByDesc(column: 'created_at')
                        ->first()
                        ->paid();
                }
            },

            function (string $status, Order $order) {
                if ($this->placeToPayService->paymentIsRejected(status: $status)) {
                    $order->cancelled();

                    Payment::query()->whereOrder(order: $order)
                        ->whereProvider(provider: Provider::PLACE_TO_PAY)
                        ->orderByDesc(column: 'created_at')
                        ->first()
                        ->rejected();
                }
            }
        ];
    }
}
