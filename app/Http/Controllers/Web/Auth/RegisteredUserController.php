<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Auth;

use App\Contracts\Repository\User\UserWriteRepositoryInterface;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Services\EntryPoint;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
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
            $user->assignRole(Roles::BUYER);

            event(new Registered($user));

            Auth::login($user);

            return redirect(EntryPoint::resolveRedirectRoute());
        } else {
            return $this->errorResponseWithBag(collection: ['server' => [trans('server.internal_error')]]);
        }
    }
}
