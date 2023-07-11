<?php

declare(strict_types=1);

namespace App\Domain\Imports\Enums;

use BenSampo\Enum\Enum;

final class ImportModules extends Enum
{
    public const PRODUCTS = 'import.products_module';
}
