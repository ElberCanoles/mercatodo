<?php

declare(strict_types=1);

namespace App\Domain\Payments\Services\PlaceToPay;

use App\Contracts\Payment\PaymentGatewayInterface;
use App\Domain\Carts\DataTransferObjects\StoreCheckoutData;
use App\Domain\Orders\Models\Order;
use Illuminate\Support\Facades\Http;
use Exception;

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
        } catch (Exception $exception) {
            logger()->error(message: 'error when obtaining place to pay session', context: [
                'module' => 'PlaceToPayService.getSession',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ]);
        }

        throw new Exception(trans(key: 'server.unavailable_service'));
    }
}
