<?php
declare(strict_types=1);

namespace App\Services\Payments\PlaceToPay;

use App\Contracts\Payment\PaymentGatewayInterface;
use App\DataTransferObjects\Checkout\StoreCheckoutData;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PlaceToPayService extends PlaceToPayBase implements PaymentGatewayInterface
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function getPaymentProcessData(StoreCheckoutData $data, int|string $reference, float $amount, $items = []): array
    {
        $response = Http::post(
            url: $this->baseUrl . '/api/session',
            data: $this->createSession(data: $data, paymentReference: $reference, amount: $amount)
        );

        if ($response->ok()) return $response->json();

        throw new Exception(trans(key: 'server.unavailable_service'));
    }

    /**
     * @throws Exception
     */
    public function getSession(int|string $requestId): array
    {
        $response = Http::post(url: $this->baseUrl . '/api/session/' . $requestId,
            data: ['auth' => $this->getAuth()]
        );

        if ($response->ok()) {
            return $response->json();
        }

        throw new Exception(trans(key: 'server.unavailable_service'));
    }

}
