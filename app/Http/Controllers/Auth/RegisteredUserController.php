<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Enums\User\RoleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\EntryPoint;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class RegisteredUserController extends Controller
{

    private UserRepositoryInterface $repository;


    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display the registration view.
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        if ($user = $this->repository->store($request->validated())) {

            $user->assignRole(RoleType::BUYER);

            event(new Registered($user));

            Auth::login($user);

            return redirect(EntryPoint::resolveRedirectRoute());
        } else {
            return redirect()->back()->withErrors(['status' => trans('server.unavailable_service')]);
        }
    }
}
