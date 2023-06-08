<?php
declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view(view: 'buyer.order.index');
    }

    public function show(Order $order): View
    {
        return view(view: 'buyer.order.show', data: [
            'order' => $order
        ]);
    }
}
