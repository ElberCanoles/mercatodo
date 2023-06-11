<?php

declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Order;

use App\Actions\Order\RetryPaymentOrderAction;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;

class OrderRetryPaymentController extends Controller
{
    public function __invoke(Order $order): RedirectResponse
    {
        return (new RetryPaymentOrderAction())->execute(order: $order);
    }
}
