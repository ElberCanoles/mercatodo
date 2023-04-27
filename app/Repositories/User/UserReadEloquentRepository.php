<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Contracts\Repository\User\UserReadRepositoryInterface;
use App\Enums\General\SystemParams;
use App\Enums\User\UserStatus;
use App\Enums\User\UserVerify;
use App\Models\User;
use App\Repositories\Repository;
use Illuminate\Pagination\LengthAwarePaginator;

class UserReadEloquentRepository extends Repository implements UserReadRepositoryInterface
{
    public function __construct(private readonly User $model)
    {
    }

    public function all(array $queryParams = [], ...$arguments): LengthAwarePaginator
    {
        $query = $this->model::query()
            ->select(columns: ['id', 'name', 'last_name', 'email', 'status', 'email_verified_at', 'created_at']);

        if ($this->isDefined(attribute: $arguments['role'] ?? null)) {
            $query = $query->whereHas(relation: 'roles', callback: function ($subQuery) use ($arguments) {
                $subQuery->where('name', $arguments['role']);
            });
        }

        if ($this->isDefined(attribute: $queryParams['name'] ?? null)) {
            $query = $query->where(column: 'name', operator: 'like', value: '%'.$queryParams['name'].'%');
        }

        if ($this->isDefined(attribute: $queryParams['last_name'] ?? null)) {
            $query = $query->where(column: 'last_name', operator: 'like', value: '%'.$queryParams['last_name'].'%');
        }

        if ($this->isDefined(attribute: $queryParams['email'] ?? null)) {
            $query = $query->where(column: 'email', operator: 'like', value: '%'.$queryParams['email'].'%');
        }

        return $query->orderBy(column: 'created_at', direction: 'DESC')
            ->orderBy(column: 'id', direction: 'DESC')
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE)->through(fn ($user) => [
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'verified_key' => $user->email_verified_at != null ? UserVerify::VERIFIED : UserVerify::NON_VERIFIED,
                'verified_value' => $user->email_verified_at != null ? trans(UserVerify::VERIFIED) : trans(UserVerify::NON_VERIFIED),
                'status_key' => $user->status,
                'status_value' => trans($user->status),
                'created_at' => $user->created_at->format('d-m-Y'),
                'edit_url' => route('admin.users.edit', ['user' => $user->id]),
            ]);
    }

    public function find(string $key, mixed $value): ?User
    {
        return $this->model->where($key, $value)->first();
    }

    public function allStatuses(): array
    {
        return collect(UserStatus::asArray())->map(fn ($status) => [
            'key' => $status,
            'value' => trans($status),
        ])->toArray();
    }
}
