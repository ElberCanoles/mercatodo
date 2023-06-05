<?php
declare(strict_types=1);

namespace App\Factories\Payment;

use App\Contracts\Payment\PaymentFactoryInterface;
use App\Contracts\Payment\PaymentGatewayInterface;
use App\Enums\Payment\Provider;
use App\Services\Payments\PlaceToPay;
use Exception;

class PaymentFactory implements PaymentFactoryInterface
{
    /**
     * @throws Exception
     */
    public function buildPaymentGateway(string $provider): PaymentGatewayInterface
    {
        return match ($provider) {
            Provider::PLACE_TO_PAY => new PlaceToPay(),
            default => throw new Exception(message: trans(key: 'validation.custom.payment.gateway_not_yet_supported'))
        };
    }
}
