<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Enums\General\SystemParams;
use App\Enums\User\RoleType;
use App\Models\User;
use App\Repositories\Repository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserEloquentRepository extends Repository implements UserRepositoryInterface
{
    private Model $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }


    public function all(array $queryParams = []): LengthAwarePaginator
    {

        $search = $queryParams['search'] ?? null;

        $query = $this->model::query()
            ->whereHas('roles', function ($subQuery) {
                $subQuery->where('name', RoleType::Buyer);
            })
            ->select('id', 'name', 'email')
            ->when($search, function ($subQuery, $search) {
                return $subQuery->where('name', 'like', '%'.$search.'%')
                             ->orWhere('email', 'like', '%'.$search.'%');
            });

        return $query->paginate(SystemParams::LengthPerPage);
    }


    public function store(array $data): ?User
    {
        try {

            return $this->model::create([
                'name' => $this->normalizeStringUsingUcwords($data['name']),
                'email' => $this->normalizeStringUsingStrtolower($data['email']),
                'password' => Hash::make($data['password']),
            ]);
        } catch (\Throwable $throwable) {
            return null;
        }
    }


    public function update(array $data, int $id): bool
    {
        $user = $this->find(id: $id);

        try {

            $user->fill([
                'name' => $this->normalizeStringUsingUcwords($data['name']),
                'email' => $this->normalizeStringUsingStrtolower($data['email']),
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


    public function delete(int $id): bool
    {
        try {
            $this->model->destroy($id);

            return true;
        } catch (\Throwable $throwable) {
            return false;
        }
    }


    public function find(int $id): ?User
    {
        return $this->model->find($id);
    }
}
