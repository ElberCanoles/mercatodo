<?php declare(strict_types=1);

namespace App\Enums\Payment;

use BenSampo\Enum\Enum;


final class PaymentStatus extends Enum
{
    const PENDING = 'payment.pending';
    const PAID = 'payment.paid';
    const REJECTED = 'payment.rejected';
    const REFUNDED = 'payment.refunded';
}
