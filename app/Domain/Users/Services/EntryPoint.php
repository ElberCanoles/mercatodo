<?php

declare(strict_types=1);

namespace App\Domain\Users\Services;

use App\Domain\Users\Enums\Roles;

class EntryPoint
{
    /**
     * Resolve entry point when user is authenticated
     */
    public static function resolveRedirectRoute(): string
    {
        $response = route(name: 'login');

        if (auth()->check()) {
            if (request()->user()->roles->contains(key: 'name', operator: '=', value: Roles::ADMINISTRATOR->value)) {
                $response = route(name: 'admin.dashboard');
            }

            if (request()->user()->roles->contains(key: 'name', operator: '=', value: Roles::BUYER->value)) {
                $response = route(name: 'buyer.dashboard');
            }
        }

        return $response;
    }
}
