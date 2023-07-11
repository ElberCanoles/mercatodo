<?php

declare(strict_types=1);

namespace App\Domain\Exports\Enums;

use BenSampo\Enum\Enum;

final class ExportModules extends Enum
{
    public const PRODUCTS = 'export.products_module';

    public const ORDERS = 'export.orders_module';
}
