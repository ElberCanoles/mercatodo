<?php

namespace App\Contracts\Payment;

use App\DataTransferObjects\Checkout\StoreCheckoutData;

interface PaymentGatewayInterface
{
    public function getProcessUrl(StoreCheckoutData $data, int|string $reference, float $amount, $items = []): string;

}
