<?php
declare(strict_types=1);

namespace App\Actions\Payment;

use App\Models\Order;
use App\Models\Payment;

class StorePaymentAction
{
    public function execute(Order $order, string $provider): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'provider' => $provider
        ]);
    }
}
