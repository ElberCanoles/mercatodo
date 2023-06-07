<?php
declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class CheckoutResultController extends Controller
{
    public function __invoke(): View
    {
        $order = Order::where('user_id', request()->user()->id)
            ->latest()
            ->first();

        return view(view: 'buyer.checkout.result', data: [
            'order' => $order
        ]);
    }
}
