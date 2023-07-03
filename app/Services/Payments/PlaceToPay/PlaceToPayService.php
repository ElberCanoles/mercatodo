<?php

declare(strict_types=1);

namespace App\Services\Payments\PlaceToPay;

use App\Contracts\Payment\PaymentGatewayInterface;
use App\DataTransferObjects\Checkout\StoreCheckoutData;
use App\Domain\Orders\Models\Order;
use Exception;
use Illuminate\Support\Facades\Http;
use Throwable;

class PlaceToPayService extends PlaceToPayBase implements PaymentGatewayInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function makePaymentProcessData(StoreCheckoutData $data, Order $order): array
    {
        $response = Http::post(
            url: $this->baseUrl . '/api/session',
            data: $this->makeSession(data: $data, order: $order)
        );

        if ($response->ok()) {
            return $response->json();
        }

        throw new Exception(trans(key: 'server.unavailable_service'));
    }

    public function decodeProcessUrl(array $paymentProcessData): string
    {
        return $paymentProcessData['processUrl'];
    }

    /**
     * @throws Exception
     */
    public function getSession(int|string $requestId): array
    {
        try {
            $response = Http::post(
                url: $this->baseUrl . '/api/session/' . $requestId,
                data: ['auth' => $this->getAuth()]
            );

            if ($response->ok()) {
                return $response->json();
            }
        } catch (Throwable $throwable) {
            report($throwable);
        }

        throw new Exception(trans(key: 'server.unavailable_service'));
    }
}
