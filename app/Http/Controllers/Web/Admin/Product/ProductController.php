<?php declare(strict_types=1);

namespace App\Http\Controllers\Web\Admin\Product;

use App\Domain\Products\Actions\DestroyProductAction;
use App\Domain\Products\Actions\StoreProductAction;
use App\Domain\Products\Actions\UpdateProductAction;
use App\Domain\Products\DataTransferObjects\StoreProductData;
use App\Domain\Products\DataTransferObjects\UpdateProductData;
use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Resources\ProductAdminResource;
use App\Domain\Shared\Enums\SystemParams;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Contracts\View\View;

final class ProductController extends Controller
{
    use MakeJsonResponse;

    public function __construct()
    {
        $this->authorizeResource(model: Product::class, parameter: 'product');
    }

    public function index(Request $request): AnonymousResourceCollection|View
    {
        if (!$request->wantsJson()) {

            $statuses = collect(ProductStatus::asArray())->map(fn($status) => [
                'key' => $status,
                'value' => trans($status),
            ])->toArray();

            return view(view: 'admin.products.index', data: [
                'statuses' => $statuses,
            ]);
        }

        $products = Product::query()
            ->select(columns: ['id', 'name', 'price', 'stock', 'status', 'created_at'])
            ->when($request->input(key: 'name'), function ($q) use ($request) {
                $q->where(column: 'name', operator: 'like', value: '%' . $request->input(key: 'name') . '%');
            })
            ->when($request->input(key: 'price'), function ($q) use ($request) {
                $q->where(column: 'price', operator: 'like', value: '%' . $request->input(key: 'price') . '%');
            })
            ->when($request->input(key: 'stock'), function ($q) use ($request) {
                $q->where(column: 'stock', operator: 'like', value: '%' . $request->input(key: 'stock') . '%');
            })
            ->when($request->input(key: 'status'), function ($q) use ($request) {
                $q->where(column: 'status', operator: '=', value: $request->input(key: 'status'));
            })
            ->orderBy(column: 'created_at', direction: 'DESC')
            ->orderBy(column: 'id', direction: 'DESC')
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE);

        return ProductAdminResource::collection($products);
    }

    public function create(): View
    {
        $statuses = collect(ProductStatus::asArray())->map(fn($status) => [
            'key' => $status,
            'value' => trans($status),
        ])->toArray();

        return view(view: 'admin.products.crud.create', data: [
            'statuses' => $statuses,
        ]);
    }

    public function store(StoreRequest $request, StoreProductAction $action): JsonResponse
    {
        $action->execute(StoreProductData::fromRequest($request));
        return $this->showMessage(message: trans(key: 'server.record_created'));
    }

    public function edit(Product $product): View
    {
        $statuses = collect(ProductStatus::asArray())->map(fn($status) => [
            'key' => $status,
            'value' => trans($status),
        ])->toArray();

        return view(view: 'admin.products.crud.edit', data: [
            'product' => $product,
            'statuses' => $statuses,
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
