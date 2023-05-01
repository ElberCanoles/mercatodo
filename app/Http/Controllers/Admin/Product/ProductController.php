<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Enums\User\RoleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ProductController extends Controller
{
    use MakeJsonResponse;

    public function __construct(
        private readonly ProductWriteRepositoryInterface $writeRepository,
        private readonly ProductReadRepositoryInterface  $readRepository
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
                    roleTarget: RoleType::ADMINISTRATOR
                )
            );
        } else {
            return view(view: 'admin.products.index', data: [
                'statuses' => $this->readRepository->allStatuses(),
            ]);
        }
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view(view: 'admin.products.crud.create', data: [
            'statuses' => $this->readRepository->allStatuses(),
        ]);
    }

    /**
     * Store a new resource in storage.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        if ($this->writeRepository->store($request->validated())) {
            return $this->showMessage(message: trans(key: 'server.record_created'));
        } else {
            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.internal_error')]]
            );
        }
    }

    /**
     * Show edit form.
     */
    public function edit(int $id): View
    {
        return view(view: 'admin.products.crud.edit', data: [
            'product' => $this->readRepository->find(key: 'id', value: $id),
            'statuses' => $this->readRepository->allStatuses(),
        ]);
    }

    /**
     * Update an existing resource in storage.
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

    /**
     * Delete a resource in storage.
     */
    public function destroy(int $id): JsonResponse
    {
        if ($this->writeRepository->delete(id: $id)) {
            return $this->showMessage(message: trans(key: 'server.record_deleted'));
        } else {
            return $this->errorResponse(
                message: trans(key: 'server.internal_error')
            );
        }
    }
}
