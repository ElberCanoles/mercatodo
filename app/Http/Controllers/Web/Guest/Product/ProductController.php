<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Guest\Product;

use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Domain\Products\Enums\ProductStatus;
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

    public function index(Request $request): JsonResponse|View
    {
        if (!$request->wantsJson()) {
            return view(view: 'guest.products.index');
        }

        return $this->successResponse(
            data: $this->readRepository->all(
                queryParams: $request->all(),
                status: ProductStatus::AVAILABLE
            )
        );
    }

    public function show(string $slug): View
    {
        return view(view: 'guest.products.show', data: [
            'product' => $this->readRepository->findAvailable(
                key: 'slug',
                value: $slug
            ) ??
                abort(code: Response::HTTP_NOT_FOUND)
        ]);
    }
}
