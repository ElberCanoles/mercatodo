<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Enums\User\RoleType;

class EntryPoint
{

    /**
     * Resolve entry point when user is authenticated
     *
     * @return string
     */
    public static function resolveRedirectRoute(): string
    {
        $response = route('login');

        if (auth()->check()) {

            if (request()->user()->hasRole(RoleType::Administrator)) {
                $response = route('admin.dashboard');
            }

            if (request()->user()->hasRole(RoleType::Buyer)) {
                $response = route('buyer.dashboard');
            }
        }

        return $response;
    }
}
