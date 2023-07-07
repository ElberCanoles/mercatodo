<?php

declare(strict_types=1);

namespace App\Domain\Products\Policies;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->permissions->contains(key: 'name', operator: '=', value: Permissions::PRODUCTS_INDEX->value);
    }

    public function view(User $user, Product $product): bool
    {
        return $user->permissions->contains(key: 'name', operator: '=', value: Permissions::PRODUCTS_SHOW->value);
    }

    public function create(User $user): bool
    {
        return $user->permissions->contains(key: 'name', operator: '=', value: Permissions::PRODUCTS_CREATE->value);
    }

    public function update(User $user, Product $product): bool
    {
        return $user->permissions->contains(key: 'name', operator: '=', value: Permissions::PRODUCTS_UPDATE->value);
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->permissions->contains(key: 'name', operator: '=', value: Permissions::PRODUCTS_DELETE->value);
    }
}
