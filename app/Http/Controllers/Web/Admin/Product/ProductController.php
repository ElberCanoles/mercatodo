<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Admin\Product;

use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Domain\Products\Models\Product;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Domain\Users\Enums\RoleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
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

    public function index(Request $request): JsonResponse|View
    {
        if (!$request->wantsJson()) {
            return view(view: 'admin.products.index', data: [
                'statuses' => $this->readRepository->allStatuses(),
            ]);
        }

        return $this->successResponse(
            data: $this->readRepository->all(
                queryParams: $request->all(),
                roleTarget: RoleType::ADMINISTRATOR
            )
        );
    }

    public function create(): View
    {
        return view(view: 'admin.products.crud.create', data: [
            'statuses' => $this->readRepository->allStatuses(),
        ]);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        if (!$this->writeRepository->store($request->validated())) {
            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.internal_error')]]
            );
        }

        return $this->showMessage(message: trans(key: 'server.record_created'));
    }

    public function edit(Product $product): View
    {
        return view(view: 'admin.products.crud.edit', data: [
            'product' => $this->readRepository->find(key: 'id', value: $product->id),
            'statuses' => $this->readRepository->allStatuses(),
        ]);
    }

    public function update(UpdateRequest $request, Product $product): JsonResponse
    {
        if (!$this->writeRepository->update(data: $request->validated(), id: $product->id)) {
            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.internal_error')]]
            );
        }

        return $this->showMessage(message: trans(key: 'server.record_updated'));
    }

    public function destroy(Product $product): JsonResponse
    {
        if (!$this->writeRepository->delete(id: $product->id)) {
            return $this->errorResponse(
                message: trans(key: 'server.internal_error')
            );
        }

        return $this->showMessage(message: trans(key: 'server.record_deleted'));
    }
}
