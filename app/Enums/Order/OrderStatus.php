<?php declare(strict_types=1);

namespace App\Enums\Order;

use BenSampo\Enum\Enum;


final class OrderStatus extends Enum
{
    const Pending = 'Pendiente';
    const Confirmed = 'Confirmada';
    const Sent = 'Enviada';
    const Delivered = 'Entregada';
    const Cancelled = 'Cancelada';
    const InReturn = 'En Devolución';
    const Returned = 'Devuelta';
    
}
