<?php

declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;
use Throwable;

class CheckoutResultController extends Controller
{
    public function __invoke(): View
    {

        try {
            $status = Order::where('user_id', auth()->user()->id)
                ->latest()
                ->first()
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
