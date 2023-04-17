<?php

declare(strict_types=1);

namespace App\Enums\Product;

use BenSampo\Enum\Enum;

final class ProductStatus extends Enum
{
    const AVAILABLE = 'product.available';

    const UNAVAILABLE = 'product.unavailable';
}
