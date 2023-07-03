<?php

declare(strict_types=1);

namespace App\Domain\Payments\Enums;

use BenSampo\Enum\Enum;

final class PaymentStatus extends Enum
{
    public const PENDING = 'payment.pending';

    public const PAID = 'payment.paid';

    public const REJECTED = 'payment.rejected';

    public const REFUNDED = 'payment.refunded';
}
