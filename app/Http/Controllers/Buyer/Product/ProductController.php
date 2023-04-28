<?php

namespace App\Http\Controllers\Buyer\Product;

use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Enums\Product\ProductStatus;
use App\Http\Controllers\Controller;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use MakeJsonResponse;

    public function __construct(private readonly ProductReadRepositoryInterface $readRepository)
    {
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
                    status: ProductStatus::AVAILABLE
                )
            );
        } else {
            return view(view: 'buyer.products.index');
        }
    }

    /**
     * Show a specific resource.
     */
    public function show(string $slug): View
    {
        return view(view: 'buyer.products.show', data: [
            'product' => $this->readRepository->findAvailable(key: 'slug', value: $slug) ?? abort(code: Response::HTTP_NOT_FOUND)
        ]);
    }
}
