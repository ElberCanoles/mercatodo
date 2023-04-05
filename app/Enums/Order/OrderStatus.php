<?php declare(strict_types=1);

namespace App\Enums\Order;

use BenSampo\Enum\Enum;


final class OrderStatus extends Enum
{
    const PENDING = 'order.pending';
    const CONFIRMED = 'order.confirmed';
    const SENT = 'order.sent';
    const DELIVERED = 'order.delivered';
    const CANCELLED = 'order.cancelled';
    const IN_RETURN = 'order.in_return';
    const RETURNED = 'order.returned';

}
