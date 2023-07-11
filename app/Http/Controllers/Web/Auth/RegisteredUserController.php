<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Auth;

use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Domain\Users\Actions\StoreUserAction;
use App\Domain\Users\DataTransferObjects\StoreUserData;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Services\EntryPoint;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

final class RegisteredUserController extends Controller
{
    use MakeJsonResponse;

    public function create(): View
    {
        return view(view: 'auth.register');
    }

    public function store(StoreRequest $request, StoreUserAction $action): RedirectResponse
    {
        $user = $action->execute(StoreUserData::fromRequest($request));

        $user->assignRole(role: Roles::BUYER);
        $user->givePermissionTo(permission: Permissions::ORDERS_INDEX);
        $user->givePermissionTo(permission: Permissions::ORDERS_SHOW);

        event(new Registered($user));

        Auth::login($user);

        return redirect(EntryPoint::resolveRedirectRoute());
    }
}
