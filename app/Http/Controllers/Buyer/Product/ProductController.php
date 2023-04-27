<?php

namespace App\Http\Controllers\Buyer\Product;

use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use MakeJsonResponse;

    public function __construct(
        private readonly ProductWriteRepositoryInterface $writeRepository,
        private readonly ProductReadRepositoryInterface $readRepository
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
                    queryParams: $request->all()
                )
            );
        } else {
            return view('buyer.products.index');
        }
    }

    public function show(string $slug): View
    {
        return view('buyer.products.show', [
            'product' => $this->readRepository->find(key: 'slug', value: $slug) ?? abort(Response::HTTP_NOT_FOUND)
        ]);
    }
}
