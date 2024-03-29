<?php

declare(strict_types=1);

namespace App\Contracts\Payment;

use App\Domain\Carts\DataTransferObjects\StoreCheckoutData;
use App\Domain\Orders\Models\Order;

interface PaymentGatewayInterface
{
    public function makePaymentProcessData(StoreCheckoutData $data, Order $order): array;

    public function decodeProcessUrl(array $paymentProcessData): string;
}
