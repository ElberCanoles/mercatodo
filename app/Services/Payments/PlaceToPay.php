<?php
declare(strict_types=1);

namespace App\Services\Payments;

use App\DataTransferObjects\Checkout\StoreCheckoutData;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PlaceToPay
{

    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config(key: 'placetopay.url');
    }

    /**
     * @throws Exception
     */
    public function pay(StoreCheckoutData $data): string
    {

        $response = Http::post(url: $this->baseUrl . '/api/session', data: $this->createSession(data: $data));

        if ($response->ok()) {
            return $response->json()['processUrl'];
        }

        throw new Exception(trans(key: 'server.unavailable_service'));
    }

    private function getAuth(): array
    {
        $nonce = Str::random();

        $nonceEncodedInBase64 = base64_encode(string: $nonce);

        $seed = date(format: 'c');

        $tranKey = base64_encode(sha1(string: $nonce . $seed . config(key: "placetopay.tran_key"), binary: true));

        return [
            "login" => config(key: 'placetopay.login'),
            "tranKey" => $tranKey,
            "nonce" => $nonceEncodedInBase64,
            "seed" => $seed
        ];
    }

    private function createSession(StoreCheckoutData $data): array
    {
        $cart = auth()->user()->cart;

        $expirationDate = Carbon::now()->addMinutes(value: 20)->format(format: 'c');

        return [
            "auth" => $this->getAuth(),
            "buyer" => [
                "document" => $data->name,
                "documentType" => $data->documentType,
                "name" => $data->name,
                "surname" => $data->lastName,
                "email" => $data->email,
                "mobile" => $data->cellPhone,
                "address" => [
                    "street" => $data->address,
                    "city" => $data->city,
                    "country" => "Colombia",
                    "phone" => $data->cellPhone
                ]
            ],
            "payment" => [
                "reference" => "12345",
                "description" => "Compra de productos",
                "amount" => [
                    "currency" => "COP",
                    "total" => $cart->total,
                ],
            ],
            "expiration" => $expirationDate,
            "returnUrl" => route(name: 'buyer.payment.response'),
            "cancelUrl" => route(name: 'buyer.payment.cancelled'),
            "ipAddress" => $_SERVER['REMOTE_ADDR'],
            "userAgent" => $_SERVER['HTTP_USER_AGENT'],
            "skipResult" => false,
            "noBuyerFill" => false
        ];
    }

}
