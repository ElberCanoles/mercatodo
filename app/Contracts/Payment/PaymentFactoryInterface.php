<?php

declare(strict_types=1);

namespace App\Contracts\Payment;

interface PaymentFactoryInterface
{
    public function buildPaymentGateway(string $provider): PaymentGatewayInterface;
}
