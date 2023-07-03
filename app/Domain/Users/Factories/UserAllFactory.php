<?php

declare(strict_types=1);

namespace App\Domain\Users\Factories;

use App\Domain\Shared\Enums\SystemParams;
use App\Domain\Shared\Traits\Utilities\CheckAttribute;
use App\Domain\Users\Enums\UserVerify;
use App\Domain\Users\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserAllFactory
{
    use CheckAttribute;

    private User $user;
    private array $queryParams;
    private array $arguments;
    private int $lengthPerPage;

    public function __construct(User $user, array $queryParams = [], ...$arguments)
    {
        $this->user = $user;
        $this->queryParams = $queryParams;
        $this->arguments = $arguments;
        $this->lengthPerPage = SystemParams::LENGTH_PER_PAGE;
    }

    public function make(): LengthAwarePaginator
    {
        $query = $this->user::query()
            ->select(columns: ['id', 'name', 'last_name', 'email', 'status', 'email_verified_at', 'created_at']);

        if ($this->isDefined(attribute: $this->arguments['role'] ?? null)) {
            $query = $query->whereHas(relation: 'roles', callback: function ($subQuery) {
                $subQuery->where('name', $this->arguments['role']);
            });
        }

        if ($this->isDefined(attribute: $this->queryParams['name'] ?? null)) {
            $query = $query->where(column: 'name', operator: 'like', value: '%'.$this->queryParams['name'].'%');
        }

        if ($this->isDefined(attribute: $this->queryParams['last_name'] ?? null)) {
            $query = $query->where(column: 'last_name', operator: 'like', value: '%'.$this->queryParams['last_name'].'%');
        }

        if ($this->isDefined(attribute: $this->queryParams['email'] ?? null)) {
            $query = $query->where(column: 'email', operator: 'like', value: '%'.$this->queryParams['email'].'%');
        }

        return $query->orderBy(column: 'created_at', direction: 'DESC')
            ->orderBy(column: 'id', direction: 'DESC')
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE)->through(fn ($user) => [
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'verified_key' => $user->email_verified_at != null ? UserVerify::VERIFIED : UserVerify::NON_VERIFIED,
                'verified_value' => $user->email_verified_at != null ? trans(key: UserVerify::VERIFIED) : trans(key: UserVerify::NON_VERIFIED),
                'status_key' => $user->status,
                'status_value' => trans($user->status),
                'created_at' => $user->created_at->format('d-m-Y'),
                'edit_url' => route(name: 'admin.users.edit', parameters: ['user' => $user->id]),
            ]);
    }
}
