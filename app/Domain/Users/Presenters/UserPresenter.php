<?php

declare(strict_types=1);

namespace App\Domain\Users\Presenters;

use App\Domain\Users\Enums\UserVerify;
use App\Domain\Users\Models\User;

class UserPresenter
{

    private static ?UserPresenter $instance = null;

    private User $user;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function verifiedKey(): string
    {
        return $this->user->email_verified_at != null ? UserVerify::VERIFIED : UserVerify::NON_VERIFIED;
    }

    public function verifiedTranslated(): string
    {
        return $this->user->email_verified_at != null ? trans(key: UserVerify::VERIFIED) : trans(key: UserVerify::NON_VERIFIED);
    }

    public function statusTranslated(): string
    {
        return trans($this->user->status);
    }

    public function createdAt(): string
    {
        return $this->user->created_at->format(format: 'd-m-Y');
    }

    public function adminEditUrl(): string
    {
        return route(name: 'admin.users.edit', parameters: ['user' => $this->user->id]);
    }

}
