<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Admin\Product;

use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Domain\Products\Actions\DestroyProductAction;
use App\Domain\Products\Actions\StoreProductAction;
use App\Domain\Products\Actions\UpdateProductAction;
use App\Domain\Products\DataTransferObjects\StoreProductData;
use App\Domain\Products\DataTransferObjects\UpdateProductData;
use App\Domain\Products\Models\Product;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Domain\Users\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ProductController extends Controller
{
    use MakeJsonResponse;

    public function __construct(
        private readonly ProductReadRepositoryInterface  $readRepository
    )
    {
        $this->authorizeResource(model: Product::class, parameter: 'product');
    }

    public function index(Request $request): JsonResponse|View
    {
        if (!$request->wantsJson()) {
            return view(view: 'admin.products.index', data: [
                'statuses' => $this->readRepository->allStatuses(),
            ]);
        }

        return $this->successResponse(
            data: $this->readRepository->all(
                queryParams: $request->all(),
                roleTarget: Roles::ADMINISTRATOR
            )
        );
    }

    public function create(): View
    {
        return view(view: 'admin.products.crud.create', data: [
            'statuses' => $this->readRepository->allStatuses(),
        ]);
    }

    public function store(StoreRequest $request, StoreProductAction $action): JsonResponse
    {
        $action->execute(StoreProductData::fromRequest($request));
        return $this->showMessage(message: trans(key: 'server.record_created'));
    }

    public function edit(Product $product): View
    {
        return view(view: 'admin.products.crud.edit', data: [
            'product' => $this->readRepository->find(key: 'id', value: $product->id),
            'statuses' => $this->readRepository->allStatuses(),
        ]);
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
