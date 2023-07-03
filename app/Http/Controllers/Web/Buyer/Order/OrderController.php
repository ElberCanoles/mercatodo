<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Buyer\Order;

use App\Domain\Orders\Models\Order;
use App\Enums\General\SystemParams;
use App\Http\Controllers\Controller;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    use MakeJsonResponse;

    public function index(): View|JsonResponse
    {
        if (!request()->wantsJson()) {
            return view(view: 'buyer.order.index');
        }

        $orders = Order::query()
            ->select(columns: ['id', 'user_id', 'amount', 'status', 'created_at'])
            ->whereUser(request()->user())
            ->orderBy(column: 'id', direction: 'DESC')
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE)->through(fn ($order) => [
                'id' => str_pad(string: (string)$order->id, length: 5, pad_string: '0', pad_type: STR_PAD_LEFT),
                'amount' => number_format(num: $order->amount, decimal_separator: ',', thousands_separator: '.'),
                'status_key' => $order->status,
                'status_value' => trans($order->status),
                'created_at' => $order->created_at->format('d-m-Y'),
                'show_url' => route(name: 'buyer.orders.show', parameters: ['order' => $order->id]),
            ]);

        return $this->successResponse(data: $orders);
    }

    public function show(Order $order): View
    {
        return view(view: 'buyer.order.show', data: [
            'order' => $order
        ]);
    }
}
