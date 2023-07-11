<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Product;

use App\Domain\Products\Actions\DestroyProductAction;
use App\Domain\Products\Actions\StoreProductAction;
use App\Domain\Products\Actions\UpdateProductAction;
use App\Domain\Products\DataTransferObjects\StoreProductData;
use App\Domain\Products\DataTransferObjects\UpdateProductData;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Resources\ProductApiResource;
use App\Domain\Shared\Enums\SystemParams;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use MakeJsonResponse;

    public function __construct()
    {
        $this->authorizeResource(model: Product::class, parameter: 'product');
    }

    public function index(): AnonymousResourceCollection
    {
        $products = QueryBuilder::for(subject: Product::class)
            ->allowedFilters(filters: ['name', 'price', 'stock', 'status'])
            ->with(relations: ['images'])
            ->select(columns: ['id', 'name', 'price', 'stock', 'status', 'description', 'created_at'])
            ->latest()
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE);

        return ProductApiResource::collection($products);
    }

    public function store(StoreRequest $request, StoreProductAction $action): JsonResponse
    {
        $action->execute(StoreProductData::fromRequest($request));
        return $this->showMessage(message: trans(key: 'server.record_created'), code: Response::HTTP_CREATED);
    }

    public function show(Product $product): ProductApiResource
    {
        return ProductApiResource::make($product);
    }

    public function update(UpdateRequest $request, Product $product, UpdateProductAction $action): JsonResponse
    {
        $action->execute(UpdateProductData::fromRequest($request), $product);
        return $this->showMessage(message: trans(key: 'server.record_updated'));
    }

    public function destroy(Product $product, DestroyProductAction $action): JsonResponse
    {
        $action->execute($product);
        return $this->showMessage(message: trans(key: 'server.record_deleted'));
    }
}
