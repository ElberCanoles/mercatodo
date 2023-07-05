<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Product;

use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Domain\Products\Models\Product;
use App\Domain\Shared\Enums\SystemParams;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Resources\Api\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use MakeJsonResponse;

    public function __construct(
        private readonly ProductWriteRepositoryInterface $writeRepository
    )
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $products = QueryBuilder::for(subject: Product::class)
            ->select(columns: ['id', 'name', 'price', 'stock', 'status', 'description', 'created_at'])
            ->with(relations: ['images'])
            ->allowedFilters(filters: ['name', 'price', 'stock', 'status'])
            ->latest()
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE);

        return ProductResource::collection($products);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        if (!$this->writeRepository->store($request->validated())) {
            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.internal_error')]]
            );
        }

        return $this->showMessage(message: trans(key: 'server.record_created'), code: Response::HTTP_CREATED);
    }

    public function show(int $id): ProductResource|JsonResponse
    {
        $product = Product::find($id);

        if ($product) {
            return ProductResource::make($product);
        }

        return $this->errorResponseWithBag(
            collection: ['server' => [trans(key: 'server.not_found')]],
            code: Response::HTTP_NOT_FOUND
        );
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $product = Product::find($id);

        if ($product) {
            if (!$this->writeRepository->update(data: $request->validated(), id: $id)) {
                return $this->errorResponseWithBag(
                    collection: ['server' => [trans(key: 'server.internal_error')]]
                );
            }
        } else {
            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.not_found')]],
                code: Response::HTTP_NOT_FOUND
            );
        }

        return $this->showMessage(message: trans(key: 'server.record_updated'));
    }

    public function destroy(int $id): JsonResponse
    {
        $product = Product::find($id);

        if ($product) {
            if (!$this->writeRepository->delete(id: $id)) {
                return $this->errorResponseWithBag(
                    collection: ['server' => [trans(key: 'server.internal_error')]]
                );
            }
        } else {
            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.not_found')]],
                code: Response::HTTP_NOT_FOUND
            );
        }

        return $this->showMessage(message: trans(key: 'server.record_deleted'));
    }
}
