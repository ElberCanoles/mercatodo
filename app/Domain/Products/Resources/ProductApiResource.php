<?php

declare(strict_types=1);

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
            'price' => $this->present()->price(),
            'stock' => $this->present()->stock(),
            'status_key' => $this->status,
            'status_value' => $this->present()->statusTranslated(),
            'description' => $this->description,
            'images' => $this->images,
            'created_at' => $this->present()->createdAt(),
            'show_url' => $this->present()->apiShowUrl(),
            'update_url' => $this->present()->apiUpdateUrl(),
            'delete_url' => $this->present()->apiDestroyUrl()
        ];
    }
}
