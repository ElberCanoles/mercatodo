<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Buyer\Checkout;

use App\Domain\Orders\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Throwable;

class CheckoutResultController extends Controller
{
    public function __invoke(): View
    {
        try {
            $status = Order::query()->findOrFail(request()->input(key: 'order'))
                ->payments()
                ->latest()
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
