<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Buyer\Payment;

use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Enums\Provider;
use App\Domain\Payments\Factories\PlaceToPay\PlaceToPayPaymentActionsFactory;
use App\Domain\Payments\Models\Payment;
use App\Domain\Payments\Services\PlaceToPay\PlaceToPayService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;
use Throwable;

class PlaceToPayController extends Controller
{
    public function __construct(private readonly PlaceToPayService $placeToPayService)
    {
    }

    public function processResponse(): RedirectResponse
    {
        $order = Order::query()->findOrFail(request()->input(key: 'order'));

        try {
            $lastPayment = Payment::query()->whereOrder(order: $order)
                ->whereProvider(provider: Provider::PLACE_TO_PAY)
                ->orderByDesc(column: 'created_at')
                ->first();

            $status = $this->placeToPayService->getSession($lastPayment->data_provider['requestId'])['status']['status'];

            $checkPaymentActions = (new PlaceToPayPaymentActionsFactory($this->placeToPayService))->make();

            foreach ($checkPaymentActions as $checkPaymentAction) {
                $checkPaymentAction($status, $order);
            }
        } catch (Throwable $exception) {
            report($exception);
        }

        return redirect()->to(path: URL::signedRoute(name: 'buyer.checkout.result', parameters: ['order' => $order->id]));
    }

    public function abortSession(): RedirectResponse
    {
        $order = Order::query()->findOrFail(request()->input(key: 'order'));

        $order->cancelled();

        Payment::query()->whereOrder(order: $order)
            ->whereProvider(provider: Provider::PLACE_TO_PAY)
            ->orderByDesc(column: 'created_at')
            ->first()
            ->rejected();

        return redirect()->to(path: URL::signedRoute(name: 'buyer.checkout.result', parameters: ['order' => $order->id]));
    }
}
