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
            'price' => $this->present()->price(),
            'stock' => $this->present()->stock(),
            'status_key' => $this->status,
            'status_value' => $this->present()->statusTranslated(),
            'created_at' => $this->present()->createdAt(),
            'edit_url' => $this->present()->adminEditUrl(),
            'delete_url' => $this->present()->adminDestroyUrl(),
        ];
    }
}
