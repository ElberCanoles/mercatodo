<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Scopes\AvailableScope;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

                    queryParams: $request->all(),
                    withoutGlobalScope: AvailableScope::class
                )
            );
        } else {

            return view('admin.products.index',[
                'statuses' => $this->repository->allStatuses()
            ]);
        }
    }


    public function create(): View
    {
        return view('admin.products.crud.create');
    }

    public function store(Request $request)
    {
        //
    }


    public function show(Product $product)
    {
        //
    }


    public function edit(Product $product): View
    {
        return view('admin.products.crud.edit',[
            'product' => $product
        ]);
    }


    public function update(Request $request, Product $product)
    {
        //
    }


    public function destroy(Product $product)
    {
        //
    }
}
