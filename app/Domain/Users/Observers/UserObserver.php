<?php

declare(strict_types=1);

namespace App\Domain\Users\Observers;

use App\Domain\Users\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $user->cart()->create();
    }
}
