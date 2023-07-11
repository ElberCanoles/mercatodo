<?php

declare(strict_types=1);

namespace App\Domain\Products\Resources;

use App\Domain\Products\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductGuestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'images' => $this->images,
            'price' => number_format(num: $this->price, decimal_separator: ',', thousands_separator: '.'),
            'stock' => number_format(num: $this->stock, decimal_separator: ',', thousands_separator: '.'),
            'add_to_cart_url' => route(name: 'buyer.products.add.to.cart', parameters: ['product' => $this->id]),
            'show_url' => route(name: 'products.show', parameters: ['slug' => $this->slug])
        ];
    }
}
