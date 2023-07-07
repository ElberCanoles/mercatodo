<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Admin\User;

use App\Contracts\Repository\User\UserReadRepositoryInterface;
use App\Contracts\Repository\User\UserWriteRepositoryInterface;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Domain\Users\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class UserController extends Controller
{
    use MakeJsonResponse;

    public function __construct(
        private readonly UserWriteRepositoryInterface $writeRepository,
        private readonly UserReadRepositoryInterface  $readRepository
    ) {
    }

    public function index(Request $request): JsonResponse|View
    {
        if (!$request->wantsJson()) {
            return view(view: 'admin.users.index');
        }

        return $this->successResponse(
            data: $this->readRepository->all(
                queryParams: $request->all(),
                role: Roles::BUYER->value
            )
        );
    }

    public function edit(int $id): View
    {
        return view(view: 'admin.users.crud.edit', data: [
            'user' => $this->readRepository->find(key: 'id', value: $id),
            'statuses' => $this->readRepository->allStatuses(),
        ]);
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        if (!$this->writeRepository->update(data: $request->validated(), id: $id)) {
            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.internal_error')]]
            );
        }

        return $this->showMessage(message: trans(key: 'server.record_updated'));
    }
}
