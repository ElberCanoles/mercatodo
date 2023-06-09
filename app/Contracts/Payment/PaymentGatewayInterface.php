<?php
declare(strict_types=1);

namespace App\Contracts\Payment;

use App\DataTransferObjects\Checkout\StoreCheckoutData;

interface PaymentGatewayInterface
{
    public function getPaymentProcessData(StoreCheckoutData $data, int|string $reference, float $amount, array $items = []): array;

    public function getProcessUrl(array $paymentProcessData): string;
}