<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Admin\User;

use App\Domain\Shared\Enums\SystemParams;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Domain\Users\Actions\UpdateUserAction;
use App\Domain\Users\DataTransferObjects\UpdateUserData;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Enums\UserStatus;
use App\Domain\Users\Models\User;
use App\Domain\Users\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Contracts\View\View;

final class UserController extends Controller
{
    use MakeJsonResponse;

    public function index(Request $request): AnonymousResourceCollection|View
    {
        if (!$request->wantsJson()) {
            return view(view: 'admin.users.index');
        }

        $users = User::query()
            ->withoutEagerLoads()
            ->whereColumContains(column: 'name', value: $request->input(key: 'name'))
            ->whereColumContains(column: 'last_name', value: $request->input(key: 'last_name'))
            ->whereColumContains(column: 'email', value: $request->input(key: 'email'))
            ->whereHas(relation: 'roles', callback: function ($subQuery) {
                $subQuery->where('name', Roles::BUYER);
            })
            ->select(columns: ['id', 'name', 'last_name', 'email', 'status', 'email_verified_at', 'created_at'])
            ->orderBy(column: 'created_at', direction: 'DESC')
            ->orderBy(column: 'id', direction: 'DESC')
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE);

        return UserResource::collection($users);
    }

    public function edit(User $user): View
    {
        $statuses = collect(UserStatus::asArray())->map(fn ($status) => [
            'key' => $status,
            'value' => trans($status),
        ])->toArray();

        return view(view: 'admin.users.crud.edit', data: [
            'user' => $user,
            'statuses' => $statuses,
        ]);
    }

    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        (new UpdateUserAction())->execute(UpdateUserData::fromRequest($request), $user);
        return $this->showMessage(message: trans(key: 'server.record_updated'));
    }
}
