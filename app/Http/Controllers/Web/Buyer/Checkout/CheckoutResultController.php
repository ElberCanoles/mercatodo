<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Buyer\Checkout;

use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Exception;

class CheckoutResultController extends Controller
{
    public function __invoke(): View
    {
        try {
            $order = Order::query()->findOrFail(request()->input(key: 'order'));

            $status = Payment::query()
                ->whereOrder(order: $order)
                ->orderByDesc(column: 'created_at')
                ->first()
                ->status;
        } catch (Exception $exception) {
            logger()->error(message: 'error getting checkout result', context: [
                'module' => 'CheckoutResultController.invoke',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ]);
        }

        return view(view: 'buyer.checkout.result', data: [
            'status' => $status ?? null
        ]);
    }
}
