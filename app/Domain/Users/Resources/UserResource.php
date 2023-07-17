<?php

declare(strict_types=1);

namespace App\Domain\Users\Resources;

use App\Domain\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'verified_key' => $this->present()->verifiedKey(),
            'verified_value' => $this->present()->verifiedTranslated(),
            'status_key' => $this->status,
            'status_value' => $this->present()->statusTranslated(),
            'created_at' => $this->present()->createdAt(),
            'edit_url' => $this->present()->adminEditUrl(),
        ];
    }
}
