<?php
declare(strict_types=1);

namespace App\Services\Payments;

use App\Contracts\Payment\PaymentGatewayInterface;
use App\DataTransferObjects\Checkout\StoreCheckoutData;
use App\Services\Payments\PlaceToPay\PlaceToPayBase;
use Exception;
use Illuminate\Support\Facades\Http;

class PlaceToPay extends PlaceToPayBase implements PaymentGatewayInterface
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function getProcessUrl(StoreCheckoutData $data, int|string $reference, float $amount, $items = []): string
    {
        $response = Http::post(
            url: $this->baseUrl . '/api/session',
            data: $this->createSession(data: $data, paymentReference: $reference, amount: $amount)
        );

        if ($response->ok()) {
            return $response->json()['processUrl'];
        }

        throw new Exception(trans(key: 'server.unavailable_service'));
    }
}
