<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Contracts\Repository\User\UserReadRepositoryInterface;
use App\Enums\User\UserStatus;
use App\Factories\User\UserAllFactory;
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
        $factory = new UserAllFactory($this->model, $queryParams, ...$arguments);
        return $factory->make();
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
