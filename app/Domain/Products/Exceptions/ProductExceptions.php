<?php

namespace App\Domain\Products\Exceptions;

use Exception;

final class ProductExceptions extends Exception
{
    public static function productWithOutStock(): self
    {
        return new ProductExceptions(message: trans(key: 'validation.custom.product.with_out_stock'));
    }
}
