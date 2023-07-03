<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Buyer\Order;

use App\Domain\Orders\Actions\RetryPaymentOrderAction;
use App\Domain\Orders\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class OrderRetryPaymentController extends Controller
{
    public function __invoke(Order $order): RedirectResponse
    {
        return (new RetryPaymentOrderAction())->execute(order: $order);
    }
}
