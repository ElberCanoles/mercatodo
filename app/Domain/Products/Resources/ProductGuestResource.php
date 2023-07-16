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
            'price' => $this->present()->price(),
            'stock' => $this->present()->stock(),
            'add_to_cart_url' => $this->present()->buyerAddToCartUrl(),
            'show_url' => $this->present()->guestShowUrl()
        ];
    }
}
