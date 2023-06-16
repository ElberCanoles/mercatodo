<?php

declare(strict_types=1);

namespace App\Contracts\Payment;

use App\DataTransferObjects\Checkout\StoreCheckoutData;
use App\Models\Order;

interface PaymentGatewayInterface
{
    public function makePaymentProcessData(StoreCheckoutData $data, Order $order): array;

    public function decodeProcessUrl(array $paymentProcessData): string;
}
