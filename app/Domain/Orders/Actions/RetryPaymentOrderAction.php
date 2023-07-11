<?php

declare(strict_types=1);

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Enums\PaymentStatus;
use App\Domain\Payments\Enums\Provider;
use App\Domain\Payments\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class RetryPaymentOrderAction
{
    public function execute(Order $order): RedirectResponse
    {
        $lastPayment = Payment::query()->whereOrder(order: $order)
            ->whereProvider(provider: Provider::PLACE_TO_PAY)
            ->orderByDesc(column: 'created_at')
            ->first();

        if (isset($lastPayment) && $lastPayment->status == PaymentStatus::PENDING->value) {
            if ($lastPayment->created_at->diffInMinutes(Carbon::now()) < ((int)config(key: 'placetopay.session_minutes_duration'))) {
                return redirect()->away(path: $lastPayment->data_provider['processUrl']);
            }
        }

        $cart = $order->user->cart;

        $cart->products()->detach();

        foreach ($order->products as $product) {
            $cart->products()->syncWithoutDetaching([
                $product->id => [
                    'quantity' => $product->pivot->quantity,
                    'name' => $product->name,
                    'price' => $product->price
                ]
            ]);
        }

        return redirect()->to(path: route(name: 'buyer.checkout.create', parameters: ['order' => $order->id]));
    }
}
