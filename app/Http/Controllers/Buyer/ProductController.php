<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
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
            return view('buyer.products.index');
        }
    }

    public function show(string $slug)
    {
        return view('buyer.products.show', [
            'product' => $this->repository->findByParam(key: 'slug', value: $slug) ?? abort(Response::HTTP_NOT_FOUND)
        ]);
    }
}
