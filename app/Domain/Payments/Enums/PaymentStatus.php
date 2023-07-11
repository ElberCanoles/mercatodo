<?php

declare(strict_types=1);

namespace App\Domain\Payments\Enums;

enum PaymentStatus: string
{
    case PENDING = 'payment.pending';

    case PAID = 'payment.paid';

    case REJECTED = 'payment.rejected';

    case REFUNDED = 'payment.refunded';

    public static function toArray(): array
    {
        return array_column(array: self::cases(), column_key: 'value');
    }
}
