<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Contracts\Repository\User\UserReadRepositoryInterface;
use App\Contracts\Repository\User\UserWriteRepositoryInterface;
use App\Enums\User\RoleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Traits\Responses\MakeJsonResponse;
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

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {
            return $this->successResponse(
                data: $this->readRepository->all(
                    queryParams: $request->all(),
                    role: RoleType::BUYER
                )
            );
        } else {
            return view(view: 'admin.users.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        return view(view: 'admin.users.crud.edit', data: [
            'user' => $this->readRepository->find(key: 'id', value: $id),
            'statuses' => $this->readRepository->allStatuses(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        if ($this->writeRepository->update(data: $request->validated(), id: $id)) {
            return $this->showMessage(message: trans(key: 'server.record_updated'));
        } else {
            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.internal_error')]]
            );
        }
    }
}
