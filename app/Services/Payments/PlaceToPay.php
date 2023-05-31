<?php
declare(strict_types=1);

namespace App\Services\Payments;

use App\DataTransferObjects\Checkout\StoreCheckoutData;
use App\Services\Buyer\CartService;
use App\Traits\Http\ConsumeExternalServices;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

class PlaceToPay
{

    use ConsumeExternalServices;

    protected string $baseUrl;

    public function __construct(private readonly CartService $cartService)
    {
        $this->baseUrl = config(key: 'placetopay.url');
    }

    /**
     * @throws GuzzleException
     */
    public function pay(StoreCheckoutData $data): ?string
    {
            $cart = $this->cartService->getFromCookie();

            $nonce = Str::random();

            $nonceEncodedInBase64 = base64_encode(string: $nonce);

            $seed = date(format: 'c');

            $tranKey = base64_encode(sha1(string: $nonce . $seed . config(key: "placetopay.tran_key"), binary: true));

            $expirationDate = Carbon::now()->addMinutes(value: 20)->format(format: 'c');

            $body = [
                "auth" => [
                    "login" => config(key: 'placetopay.login'),
                    "tranKey" => $tranKey,
                    "nonce" => $nonceEncodedInBase64,
                    "seed" => $seed
                ],
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
                "userAgent" => "PlacetoPay Sandbox",
                "skipResult" => false,
                "noBuyerFill" => false,
                "type" => "checkin"
            ];

            $response = $this->makeRequest(method: 'POST', requestUrl: '/api/session', formParams: $body);

            return $response->processUrl;
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }
}
