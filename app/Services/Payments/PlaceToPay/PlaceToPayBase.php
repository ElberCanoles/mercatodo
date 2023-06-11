<?php

declare(strict_types=1);

namespace App\Services\Payments\PlaceToPay;

use App\DataTransferObjects\Checkout\StoreCheckoutData;
use App\Enums\Payment\PlaceToPay\ApprovedStatuses;
use App\Enums\Payment\PlaceToPay\PendingStatuses;
use App\Enums\Payment\PlaceToPay\RejectedStatuses;
use App\Models\Order;
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

    protected function createSession(StoreCheckoutData $data, Order $order): array
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
                "reference" => $order->id,
                "description" => trans(key: 'order.description_checkout'),
                "amount" => [
                    "currency" => config(key: 'placetopay.currency'),
                    "total" => $order->amount,
                ],
            ],
            "expiration" => $expirationDate,
            "returnUrl" => route(name: 'buyer.placetopay.payment.response', parameters: ['order' => $order->id]),
            "cancelUrl" => route(name: 'buyer.placetopay.payment.cancelled', parameters: ['order' => $order->id]),
            "ipAddress" => $_SERVER['REMOTE_ADDR'],
            "userAgent" => $_SERVER['HTTP_USER_AGENT'],
            "skipResult" => false,
            "noBuyerFill" => false
        ];
    }

    public function paymentIsApproved(string $status): bool
    {
        return (in_array(needle: $status, haystack: ApprovedStatuses::asArray()));
    }

    public function paymentIsPending(string $status): bool
    {
        return (in_array(needle: $status, haystack: PendingStatuses::asArray()));
    }

    public function paymentIsRejected(string $status): bool
    {
        return (in_array(needle: $status, haystack: RejectedStatuses::asArray()));
    }
}
