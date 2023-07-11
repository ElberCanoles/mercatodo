<?php declare(strict_types=1);

namespace App\Domain\Users\Resources;

use App\Domain\Users\Enums\UserVerify;
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
            'verified_key' => $this->email_verified_at != null ? UserVerify::VERIFIED : UserVerify::NON_VERIFIED,
            'verified_value' => $this->email_verified_at != null ? trans(key: UserVerify::VERIFIED) : trans(key: UserVerify::NON_VERIFIED),
            'status_key' => $this->status,
            'status_value' => trans($this->status),
            'created_at' => $this->created_at->format(format: 'd-m-Y'),
            'edit_url' => route(name: 'admin.users.edit', parameters: ['user' => $this->id]),
        ];
    }
}
