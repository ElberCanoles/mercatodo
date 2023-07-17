<?php

namespace App\Http\Middleware;

use App\Domain\Users\Enums\UserStatus;
use App\Domain\Users\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyIfUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var User $user
         */
        $user = User::query()->withoutEagerLoads()->find(auth()->user()->getAuthIdentifier());

        if ($user->status === UserStatus::INACTIVE) {
            abort(code: Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
