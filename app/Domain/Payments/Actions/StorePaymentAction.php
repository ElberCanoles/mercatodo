<?php

declare(strict_types=1);

namespace App\Domain\Payments\Actions;

use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Models\Payment;

class StorePaymentAction
{
    public function execute(Order $order, string $provider, mixed $dataProvider): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'provider' => $provider,
            'data_provider' => $dataProvider
        ]);
    }
}
