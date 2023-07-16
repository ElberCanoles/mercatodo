<?php

declare(strict_types=1);

namespace App\Domain\Orders\Resources;

use App\Domain\Orders\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->present()->strPadId(),
            'amount' => $this->present()->amount(),
            'status_key' => $this->status,
            'status_value' => $this->present()->statusTranslated(),
            'created_at' => $this->present()->createdAt(),
            'show_url' => $this->present()->buyerShowUrl(),
        ];
    }
}
