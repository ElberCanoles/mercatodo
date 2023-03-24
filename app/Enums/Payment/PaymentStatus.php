<?php declare(strict_types=1);

namespace App\Enums\Payment;

use BenSampo\Enum\Enum;


final class PaymentStatus extends Enum
{
    const Pending = 'Pendiente';
    const Paid = 'Pagado';
    const Rejected = 'Rechazado';
    const Refunded = 'Reembolsado';
}
