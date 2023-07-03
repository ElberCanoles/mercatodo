<?php declare(strict_types=1);

namespace App\Domain\Exports\Enums;

use BenSampo\Enum\Enum;

final class ExportModules extends Enum
{
    const PRODUCTS = 'export.products_module';

    const ORDERS = 'export.orders_module';
}
