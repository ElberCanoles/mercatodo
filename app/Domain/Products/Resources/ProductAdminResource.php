<?php

declare(strict_types=1);

namespace App\Domain\Products\Resources;

use App\Domain\Products\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductAdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => number_format(num: $this->price, decimal_separator: ',', thousands_separator: '.'),
            'stock' => number_format(num: $this->stock, decimal_separator: ',', thousands_separator: '.'),
            'status_key' => $this->status,
            'status_value' => trans($this->status),
            'created_at' => $this->created_at->format(format: 'd-m-Y'),
            'edit_url' => route(name: 'admin.products.edit', parameters: ['product' => $this->id]),
            'delete_url' => route(name: 'admin.products.destroy', parameters: ['product' => $this->id]),
        ];
    }
}
