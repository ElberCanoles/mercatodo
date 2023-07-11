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
            'id' => str_pad(string: (string)$this->id, length: 5, pad_string: '0', pad_type: STR_PAD_LEFT),
            'amount' => number_format(num: $this->amount, decimal_separator: ',', thousands_separator: '.'),
            'status_key' => $this->status,
            'status_value' => trans($this->status),
            'created_at' => $this->created_at->format(format: 'd-m-Y'),
            'show_url' => route(name: 'buyer.orders.show', parameters: ['order' => $this->id]),
        ];
    }
}
