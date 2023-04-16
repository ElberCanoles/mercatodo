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
     *
     * @param Request $request
     * @return JsonResponse|View
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
                'statuses' => $this->repository->allStatuses()
            ]);
        }
    }

    /**
     * Show create form
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.products.crud.create', [
            'statuses' => $this->repository->allStatuses()
        ]);
    }

    /**
     * Store a new resource in storage.
     *
     * @param UpdateRequest $request
     * @param integer $id
     * @return JsonResponse
     */
    public function store(StoreRequest $request)
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


    public function show(Product $product)
    {
        //
    }


    public function edit(Product $product): View
    {
        return view('admin.products.crud.edit', [
            'product' => $product,
            'statuses' => $this->repository->allStatuses()
        ]);
    }


    public function update(UpdateRequest $request, Product $product)
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


    public function destroy(Product $product)
    {
        //
    }
}
