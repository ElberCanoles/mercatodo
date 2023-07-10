<?php declare(strict_types=1);

namespace App\Domain\Users\Actions;

use App\Domain\Users\DataTransferObjects\UpdateUserPasswordData;
use App\Domain\Users\Models\User;

class UpdateUserPasswordAction
{
    public function execute(UpdateUserPasswordData $data, User $user): void
    {
        $user->password = $data->password;
        $user->save();
    }
}
