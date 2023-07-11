<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Guest\Product;

use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Resources\ProductGuestResource;
use App\Domain\Shared\Enums\SystemParams;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    use MakeJsonResponse;

    public function index(Request $request): AnonymousResourceCollection|View
    {
        if (!$request->wantsJson()) {
            return view(view: 'guest.products.index');
        }

        $products = Product::query()
            ->select(columns: ['id', 'name', 'slug', 'price', 'stock'])
            ->where(column: 'status', operator: '=', value: ProductStatus::AVAILABLE)
            ->when($request->input(key: 'name'), function ($q) use ($request) {
                $q->where(column: 'name', operator: 'like', value: '%' . $request->input(key: 'name') . '%');
            })
            ->when($request->input(key: 'minimum_price'), function ($q) use ($request) {
                $q->where(column: 'price', operator: '>=', value: $request->input(key: 'minimum_price'));
            })
            ->when($request->input(key: 'maximum_price'), function ($q) use ($request) {
                $q->where(column: 'price', operator: '<=', value: $request->input(key: 'maximum_price'));
            })
            ->orderBy(column: 'products.created_at', direction: 'DESC')
            ->orderBy(column: 'products.id', direction: 'DESC')
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE);

        return ProductGuestResource::collection($products);
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->where(column: 'slug', operator: '=', value: $slug)
            ->where(column: 'status', operator: '=', value: ProductStatus::AVAILABLE)
            ->firstOrFail();

        return view(view: 'guest.products.show', data: [
            'product' => $product
        ]);
    }
}
