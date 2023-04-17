<?php

declare(strict_types=1);

namespace App\Enums\Order;

use BenSampo\Enum\Enum;

final class OrderStatus extends Enum
{
    public const PENDING = 'order.pending';

    public const CONFIRMED = 'order.confirmed';

    public const SENT = 'order.sent';

    public const DELIVERED = 'order.delivered';

    public const CANCELLED = 'order.cancelled';

    public const IN_RETURN = 'order.in_return';

    public const RETURNED = 'order.returned';
}
