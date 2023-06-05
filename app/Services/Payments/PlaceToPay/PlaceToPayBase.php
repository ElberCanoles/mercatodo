<?php

namespace App\Services\Payments\PlaceToPay;

use App\DataTransferObjects\Checkout\StoreCheckoutData;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PlaceToPayBase
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config(key: 'placetopay.url');
    }

    protected function getAuth(): array
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

    protected function createSession(StoreCheckoutData $data, string|int $paymentReference, float $amount): array
    {
        $expirationDate = Carbon::now()
            ->addMinutes(value: config(key: 'placetopay.session_minutes_duration'))
            ->format(format: 'c');

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
                    "country" => config(key: 'placetopay.country'),
                    "phone" => $data->cellPhone
                ]
            ],
            "payment" => [
                "reference" => $paymentReference,
                "description" => "Compra de productos",
                "amount" => [
                    "currency" => config(key: 'placetopay.currency'),
                    "total" => $amount,
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
