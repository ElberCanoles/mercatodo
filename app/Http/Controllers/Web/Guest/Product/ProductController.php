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
            ->whereStatus(status: ProductStatus::AVAILABLE)
            ->whereColumContains(column: 'name', value: $request->input(key: 'name'))
            ->wherePriceGreaterThanOrEqualsTo(value: $request->input(key: 'minimum_price'))
            ->wherePriceLessThanOrEqualsTo(value: $request->input(key: 'maximum_price'))
            ->select(columns: ['id', 'name', 'slug', 'price', 'stock'])
            ->orderBy(column: 'products.created_at', direction: 'DESC')
            ->orderBy(column: 'products.id', direction: 'DESC')
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE);

        return ProductGuestResource::collection($products);
    }

    public function show(string $slug): View
    {
        $product = Product::query()
            ->where(column: 'slug', operator: '=', value: $slug)
            ->whereStatus(status: ProductStatus::AVAILABLE)
            ->firstOrFail();

        return view(view: 'guest.products.show', data: [
            'product' => $product
        ]);
    }
}
