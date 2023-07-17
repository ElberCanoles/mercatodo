<?php

declare(strict_types=1);

namespace App\Domain\Products\Enums;

use BenSampo\Enum\Enum;

final class ProductStatus extends Enum
{
    public const AVAILABLE = 'product.available';

    public const UNAVAILABLE = 'product.unavailable';
}
