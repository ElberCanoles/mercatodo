<?php declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domain\Users\Enums\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @param string $role
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (request()->user()->roles->contains(key: 'name', operator: '=', value: $role)) return $next($request);

        abort(code: Response::HTTP_FORBIDDEN);
    }
}
