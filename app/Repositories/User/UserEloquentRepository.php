<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Enums\General\SystemParams;
use App\Enums\User\RoleType;
use App\Enums\User\UserStatus;
use App\Enums\User\UserVerify;
use App\Models\User;
use App\Repositories\Repository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class UserEloquentRepository extends Repository implements UserRepositoryInterface
{

    private Model $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }


    /**
     * Get all users
     *
     * @param array $queryParams
     * @return LengthAwarePaginator
     */
    public function all(array $queryParams = []): LengthAwarePaginator
    {

        $query = $this->model::query()
            ->whereHas('roles', function ($subQuery) {
                $subQuery->where('name', RoleType::BUYER);
            })
            ->select('id', 'name', 'last_name', 'email', 'status', 'email_verified_at', 'created_at');

        if ($this->isDefined($queryParams['name'] ?? null)) {
            $query = $query->where('name', 'like', '%' . $queryParams['name'] . '%');
        }

        if ($this->isDefined($queryParams['last_name'] ?? null)) {
            $query = $query->where('last_name', 'like', '%' . $queryParams['last_name'] . '%');
        }

        if ($this->isDefined($queryParams['email'] ?? null)) {
            $query = $query->where('email', 'like', '%' . $queryParams['email'] . '%');
        }


        return $query->orderBy('created_at', 'DESC')
            ->paginate(SystemParams::LENGTH_PER_PAGE)->through(fn ($user) => [
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'verified' => $user->email_verified_at != null ? UserVerify::VERIFIED : UserVerify::NON_VERIFIED,
                'status' => $user->status,
                'created_at' => $user->created_at->format('d-m-Y'),
                'edit_url' => route('admin.users.edit', ['user' => $user->id]),
            ]);
    }


    /**
     * Store a new user on database
     *
     * @param array $data
     * @return User|null
     */
    public function store(array $data): ?User
    {
        try {

            return $this->model::create([
                'name' => $this->normalizeStringUsingUcwords($data['name']),
                'last_name' => $this->normalizeStringUsingUcwords($data['last_name']),
                'email' => $this->normalizeStringUsingStrtolower($data['email']),
                'password' => $this->normalizeStringUsingHash($data['password']),
            ]);
        } catch (\Throwable $throwable) {
            return null;
        }
    }


    /**
     * Update one user on database
     *
     * @param array $data
     * @param integer $id
     * @return boolean
     */
    public function update(array $data, int $id): bool
    {
        $user = $this->find(id: $id);

        try {

            $user->fill([
                'name' => $this->normalizeStringUsingUcwords($data['name']),
                'last_name' => $this->normalizeStringUsingUcwords($data['last_name']),
                'email' => $this->normalizeStringUsingStrtolower($data['email']),
                'status' => $data['status'] ?? $user->status,
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
                $user->sendEmailVerificationNotification();
            }

            return $user->save();
        } catch (\Throwable $throwable) {
            return false;
        }
    }


    /**
     * Delete one user on database
     *
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): bool
    {
        try {
            $this->model->destroy($id);

            return true;
        } catch (\Throwable $throwable) {
            return false;
        }
    }


    /**
     * Get one user record by id
     *
     * @param integer $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        return $this->model->find($id);
    }


    /**
     * Get all users statuses
     *
     * @return array
     */
    public function allStatuses(): array
    {
        return UserStatus::asArray();
    }


    /**
     * Update user password
     *
     * @param array $data
     * @param integer $id
     * @return boolean
     */
    public function updatePassword(array $data, int $id): bool
    {
        $user = $this->find(id: $id);

        try {
            $user->password = $this->normalizeStringUsingHash($data['password']);
            return $user->save();
        } catch (\Throwable $throwable) {
            return false;
        }
    }
}
