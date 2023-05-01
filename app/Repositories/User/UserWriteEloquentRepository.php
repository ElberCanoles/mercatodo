<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Contracts\Repository\User\UserWriteRepositoryInterface;
use App\Models\User;
use App\Repositories\Repository;

final class UserWriteEloquentRepository extends Repository implements UserWriteRepositoryInterface
{
    public function __construct(private readonly User $model)
    {
    }

    /**
     * Store a new user on database
     */
    public function store(array $data): ?User
    {
        try {
            return $this->model::create([
                'name' => $this->normalizeStringUsingUcwords(input: $data['name']),
                'last_name' => $this->normalizeStringUsingUcwords(input: $data['last_name']),
                'email' => $this->normalizeStringUsingStrtolower(input: $data['email']),
                'password' => $this->normalizeStringUsingHash(input: $data['password']),
            ]);
        } catch (\Throwable $throwable) {
            return null;
        }
    }

    /**
     * Update one user on database
     */
    public function update(array $data, int $id): bool
    {
        $user = $this->model::find($id);

        try {
            $user->fill([
                'name' => $this->normalizeStringUsingUcwords(input: $data['name']),
                'last_name' => $this->normalizeStringUsingUcwords(input: $data['last_name']),
                'email' => $this->normalizeStringUsingStrtolower(input: $data['email'] ?? null) ?? $user->email,
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
     * Update user password
     */
    public function updatePassword(array $data, int $id): bool
    {
        $user = $this->model::find($id);

        try {
            $user->password = $this->normalizeStringUsingHash(input: $data['password']);

            return $user->save();
        } catch (\Throwable $throwable) {
            return false;
        }
    }
}
