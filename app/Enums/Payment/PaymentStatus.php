<?php declare(strict_types=1);

namespace App\Enums\Payment;

use BenSampo\Enum\Enum;


final class PaymentStatus extends Enum
{
    const PENDING = 'Pendiente';
    const PAID = 'Pagado';
    const REJECTED = 'Rechazado';
    const REFUNDED = 'Reembolsado';
}
