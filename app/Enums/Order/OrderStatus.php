<?php declare(strict_types=1);

namespace App\Enums\Order;

use BenSampo\Enum\Enum;


final class OrderStatus extends Enum
{
    const PENDING = 'Pendiente';
    const CONFIRMED = 'Confirmada';
    const SENT = 'Enviada';
    const DELIVERED = 'Entregada';
    const CANCELLED = 'Cancelada';
    const IN_RETURN = 'En Devolución';
    const RETURNED = 'Devuelta';

}
