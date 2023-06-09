<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Enums\User\RoleType;

class EntryPoint
{
    /**
     * Resolve entry point when user is authenticated
     */
    public static function resolveRedirectRoute(): string
    {
        $response = route('login');

        if (auth()->check()) {
            if (request()->user()->hasRole(RoleType::ADMINISTRATOR)) {
                $response = route('admin.dashboard');
            }

            if (request()->user()->hasRole(RoleType::BUYER)) {
                $response = route('buyer.dashboard');
            }
        }

        return $response;
    }
}
