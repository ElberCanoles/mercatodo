<?php

declare(strict_types=1);

namespace App\Domain\Users\Actions;

use App\Domain\Users\DataTransferObjects\StoreUserData;
use App\Domain\Users\Models\User;

class StoreUserAction
{
    public function execute(StoreUserData $data): User
    {
        return User::create([
            'name' => $data->name,
            'last_name' => $data->lastName,
            'email' => $data->email,
            'password' => $data->password
        ]);
    }
}
