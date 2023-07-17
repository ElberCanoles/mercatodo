<?php

declare(strict_types=1);

namespace App\Domain\Payments\QueryBuilders;

use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Enums\PaymentStatus;
use App\Domain\Payments\Enums\Provider;
use App\Domain\Payments\Models\Payment;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Payment create(array $attributes = [])
 * @method static Payment|null first()
 * @method static Payment|null find($id, $columns = ['*'])
 */
class PaymentQueryBuilder extends Builder
{
    public function whereOrder(Order $order): self
    {
        return $this->where(column: 'order_id', operator: '=', value: $order->id);
    }

    public function whereProvider(Provider $provider): self
    {
        return $this->where(column: 'provider', operator: '=', value: $provider->value);
    }

    public function whereStatus(PaymentStatus $status): self
    {
        return $this->where(column: 'status', operator: '=', value: $status->value);
    }
}
