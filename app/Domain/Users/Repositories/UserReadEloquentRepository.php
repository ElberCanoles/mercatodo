<?php

declare(strict_types=1);

namespace App\Domain\Users\Repositories;

use App\Contracts\Repository\Base\Repository;
use App\Contracts\Repository\User\UserReadRepositoryInterface;
use App\Domain\Users\Enums\UserStatus;
use App\Domain\Users\Factories\UserAllFactory;
use App\Domain\Users\Models\User;
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
