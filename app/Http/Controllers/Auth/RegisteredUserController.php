<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Contracts\Repository\User\UserWriteRepositoryInterface;
use App\Enums\User\RoleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Services\Auth\EntryPoint;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class RegisteredUserController extends Controller
{
    use MakeJsonResponse;

    public function __construct(private readonly UserWriteRepositoryInterface $writeRepository)
    {
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(StoreRequest $request): RedirectResponse|JsonResponse
    {
        if ($user = $this->writeRepository->store($request->validated())) {
            $user->assignRole(RoleType::BUYER);

            event(new Registered($user));

            Auth::login($user);

            return redirect(EntryPoint::resolveRedirectRoute());
        } else {
            return $this->errorResponseWithBag(collection: ['server' => [trans('server.internal_error')]]);
        }
    }
}
