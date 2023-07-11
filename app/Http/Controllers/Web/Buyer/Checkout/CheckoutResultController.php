<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Buyer\Checkout;

use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Throwable;

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

        } catch (Throwable $throwable) {
            report($throwable);
        }

        return view(view: 'buyer.checkout.result', data: [
            'status' => $status ?? null
        ]);
    }
}
