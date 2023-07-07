<?php declare(strict_types=1);

namespace App\Domain\Products\Actions;

use App\Domain\Products\Models\Product;

class DestroyProductAction
{
    public function execute(Product $product): void
    {
        $product->delete();
    }
}
