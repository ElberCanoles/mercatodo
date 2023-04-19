<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

final class ProductController extends Controller
{
    use MakeJsonResponse;

    public function __construct(private readonly ProductRepositoryInterface $repository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {
            return $this->successResponse(
                data: $this->repository->all(
                    queryParams: $request->all()
                )
            );
        } else {
            return view('admin.products.index', [
                'statuses' => $this->repository->allStatuses(),
            ]);
        }
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view('admin.products.crud.create', [
            'statuses' => $this->repository->allStatuses(),
        ]);
    }

    /**
     * Store a new resource in storage.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        if ($this->repository->store($request->validated())) {
            return $this->showMessage(message: trans('server.record_created'));
        } else {
            return $this->errorResponseWithBag(
                collection: ['server' => [trans('server.internal_error')]],
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Show edit form.
     */
    public function edit(Product $product): View
    {
        return view('admin.products.crud.edit', [
            'product' => $product,
            'statuses' => $this->repository->allStatuses(),
        ]);
    }

    /**
     * Update a existing resource in storage.
     */
    public function update(UpdateRequest $request, Product $product): JsonResponse
    {
        if ($this->repository->update($request->validated(), $product->id)) {
            return $this->showMessage(message: trans('server.record_updated'));
        } else {
            return $this->errorResponseWithBag(
                collection: ['server' => [trans('server.internal_error')]],
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Delete a resource in storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        if ($this->repository->delete($product->id)) {
            return $this->showMessage(message: trans('server.record_deleted'));
        } else {
            return $this->errorResponse(
                message: trans('server.internal_error')
            );
        }
    }
}
