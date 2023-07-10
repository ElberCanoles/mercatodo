<?php declare(strict_types=1);

namespace App\Domain\Users\Actions;

use App\Domain\Users\DataTransferObjects\UpdateUserData;
use App\Domain\Users\Models\User;

class UpdateUserAction
{
    public function execute(UpdateUserData $data, User $user): void
    {
        $user->fill([
            'name' => $data->name,
            'last_name' => $data->lastName,
            'email' => $data->email,
            'status' => $data->status ?? $user->status
        ]);

        if ($user->isDirty(attributes: 'email')) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        if ($user->isDirty()) {
            $user->save();
        }
    }

}
