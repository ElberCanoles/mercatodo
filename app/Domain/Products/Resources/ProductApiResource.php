<?php declare(strict_types=1);

namespace App\Domain\Products\Resources;

use App\Domain\Products\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductApiResource extends JsonResource
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
            'description' => $this->description,
            'images' => $this->images,
            'created_at' => $this->created_at->format(format: 'd-m-Y'),
            'show_url' => route(name: 'api.products.show', parameters: ['product' => $this->id]),
            'update_url' => route(name: 'api.products.update', parameters: ['product' => $this->id]),
            'delete_url' => route(name: 'api.products.destroy', parameters: ['product' => $this->id])
        ];
    }
}
