<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt(credentials: $request->only(keys: ['email', 'password']))) {
            return response()->json(data: [
                'message' => trans(key: 'auth.failed')
            ], status: Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::query()->where(column: 'email', operator: '=', value: $request->input(key: 'email'))->first();

        $token = $user->createToken(Str::random())->plainTextToken;

        return response()->json(data: [
            'access_token' => $token
        ]);
    }
}
