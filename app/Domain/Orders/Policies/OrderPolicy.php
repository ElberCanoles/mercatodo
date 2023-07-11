<?php

declare(strict_types=1);

namespace App\Domain\Orders\Policies;

use App\Domain\Orders\Models\Order;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->permissions->contains(key: 'name', operator: '=', value: Permissions::ORDERS_INDEX->value);
    }

    public function view(User $user, Order $order): bool
    {
        return $user->permissions->contains(key: 'name', operator: '=', value: Permissions::ORDERS_SHOW->value)
            && $order->user_id == $user->id;
    }

    public function create(User $user): bool
    {
        return $user->permissions->contains(key: 'name', operator: '=', value: Permissions::ORDERS_CREATE->value);
    }

    public function update(User $user, Order $order): bool
    {
        return $user->permissions->contains(key: 'name', operator: '=', value: Permissions::ORDERS_UPDATE->value)
            && $order->user_id == $user->id;
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->permissions->contains(key: 'name', operator: '=', value: Permissions::ORDERS_DELETE->value)
            && $order->user_id == $user->id;
    }
}
