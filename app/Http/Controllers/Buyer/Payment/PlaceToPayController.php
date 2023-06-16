<?php

declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Payment;

use App\Factories\PlaceToPay\PlaceToPayPaymentActionsFactory;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payments\PlaceToPay\PlaceToPayService;
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
        $order = Order::findOrFail(request()->input(key: 'order'));

        try {
            $status = $this->placeToPayService->getSession($order->payments()->latest()->first()->data_provider['requestId'])['status']['status'];

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
        $order = Order::findOrFail(request()->input(key: 'order'));

        $order->cancelled();
        $order->payments()->latest()->first()->rejected();

        return redirect()->to(path: URL::signedRoute(name: 'buyer.checkout.result', parameters: ['order' => $order->id]));
    }
}
