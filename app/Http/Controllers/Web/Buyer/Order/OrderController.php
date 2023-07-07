<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Buyer\Order;

use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Resources\OrderResource;
use App\Domain\Shared\Enums\SystemParams;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\View\View;

class OrderController extends Controller
{
    use MakeJsonResponse;

    public function __construct()
    {
        $this->authorizeResource(model: Order::class, parameter: 'order');
    }

    public function index(): View|AnonymousResourceCollection
    {
        if (!request()->wantsJson()) {
            return view(view: 'buyer.order.index');
        }

        $orders = Order::query()
            ->whereUser(request()->user())
            ->select(columns: ['id', 'user_id', 'amount', 'status', 'created_at'])
            ->orderBy(column: 'id', direction: 'DESC')
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE);

        return OrderResource::collection($orders);
    }

    public function show(Order $order): View
    {
        return view(view: 'buyer.order.show', data: [
            'order' => $order
        ]);
    }
}
