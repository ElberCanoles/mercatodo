<?php

declare(strict_types=1);

namespace App\Domain\Payments\Factories;

use App\Contracts\Payment\PaymentFactoryInterface;
use App\Contracts\Payment\PaymentGatewayInterface;
use App\Domain\Payments\Enums\Provider;
use App\Services\Payments\PlaceToPay\PlaceToPayService;
use Exception;

class PaymentFactory implements PaymentFactoryInterface
{
    /**
     * @throws Exception
     */
    public function buildPaymentGateway(string $provider): PaymentGatewayInterface
    {
        return match ($provider) {
            Provider::PLACE_TO_PAY => new PlaceToPayService(),
            default => throw new Exception(message: trans(key: 'validation.custom.payment.gateway_not_yet_supported'))
        };
    }
}
